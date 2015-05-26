<?php
App::uses('AppModel', 'Model');
App::uses('CarType', 'Model');
App::uses('CarSubtype', 'Model');
App::uses('CarSubsection', 'Model');
App::uses('Article', 'Model');
App::import('Vendor', 'simple_html_dom');
App::uses('Translit', 'Article.Vendor');
class ZzapParser extends AppModel {
	
	const MAX_CONNECTION = 10;
	public $useTable = false;
	
	protected function _beforeInit() {
		$this->loadModel('CarType');
		$this->loadModel('CarSubtype');
		$this->loadModel('CarSubsection');
		$this->loadModel('Article');
	}

		private function getBrandUrl($brandId){
		
		$brandUrl = $this->CarType->find('list', array(
							'fields' => array('title', 'zzap_url'),
							'conditions' => array('id'=>$brandId,'zzap_url !=' => NULL),
						));
		return $brandUrl;
	}
	
	private function getModelsListForBrand($brandId){
		
		$modelList = $this->CarSubtype->find('list', array(
							'fields' => array('id', 'slug'),
							'conditions'=>array('cat_id'=>$brandId)
						));
		return $modelList;
	}
	
	private function getExistSubsections($catId){
		$subsections = $this->CarSubsection->find('list', array(
							'fields' => array('id', 'slug'),
							'conditions'=>array('cat_id'=>$catId)
						));
		return $subsections;
	}


	private function saveSections($parsedSections,$brandId){
		$modelList = $this->getModelsListForBrand($brandId);
		foreach($parsedSections as $modelName=>$sectionList){
			$modelName = trim($modelName);
			$modelName = str_replace('&nbsp;', '', $modelName);
			$catId = array_search(Translit::convert($modelName,true), $modelList);
			if(!$catId){
				$data['CarSubtype'] = array('object_type'=>'CarSubtype','title'=>$modelName,'slug'=>Translit::convert($modelName,true),'cat_id'=>$brandId);
				$this->CarSubtype->saveAll($data);
				$catId = $this->CarSubtype->id;
			}
			
			$existSubsection = $this->getExistSubsections($catId);
			foreach ($sectionList as $section){
				$section = trim($section);
				$sectionId = array_search(Translit::convert($section,true), $existSubsection);
				if(!$sectionId){
					$saveData['CarSubsection'] = array('object_type'=>'CarSubsection','cat_id'=>$catId,'title'=>$section,'slug'=>Translit::convert($section,true));
					$this->CarSubsection->saveAll($saveData);
				}
			}
		}
	}


	public function saveSubsections($brandId){
		$brandUrl = $this->getBrandUrl($brandId);
		$brandHtmlContent = $this->doMulticurl($brandUrl);
		$modelUrls = $this->parseModelList($brandHtmlContent);
		if($modelUrls){
			foreach ($modelUrls as $urlCollection){
				$sections = $this->doMulticurl($urlCollection);
				$parsedSections = $this->parseSections($sections);
				$this->saveSections($parsedSections,$brandId);
			}
		}
	}

	private function parseModelList($htmlContent){
		$modelLinks = array();
		foreach ($htmlContent as $brandLink=>$brandHtml){
			$domObj = str_get_html($brandHtml);
			$modelMenu = $domObj->find('div#ctl00_BodyPlace_ClassCarTabControl',0);
			if(!$modelMenu){
				continue;
			}
			$menuListElements = $modelMenu->find('li.dxtc-tab a.dxtc-link');
			foreach ($menuListElements as $link){
				$href = $link->attr['href'];
				if($href){
					$modelLinks[$brandLink][$link->plaintext] = 'http://www.zzap.ru/'.$href;
				}
			}
		}
		return $modelLinks;
	}
	
	private function parseSections($sectionContent){
		$sectionList = array();
		foreach ($sectionContent as $modelName=>$sectionsHtml){
			$domObj = str_get_html($sectionsHtml);
			$sectionsTable = $domObj->find('table#ctl00_BodyPlace_ManufactTitleIndex_CT',0);
			if(!$sectionsTable){
				continue;
			}
			$sectionListElements = $sectionsTable->find('div.dxti-i a.f14');
			foreach ($sectionListElements as $sec){
				$sectionName = $sec->plaintext;
				if($sectionName){
					$sectionList[$modelName][] = $sectionName;
				}
			}
		}
		return $sectionList;
	}

	private function doMulticurl($linkArray){
		if(!$linkArray){
			return;
		}
		$linkCollection = $linkArray;
		$htmlArray = array();
		
		$this->multiHandle = curl_multi_init();

		for ($i = 0; $i < self::MAX_CONNECTION; $i++) {
			if(!count($linkArray)){
				break;
			}
			$this->add_url_to_multi_handle(array_pop($linkArray));
		}
		
		$active = null;
		do {
			$mrc = curl_multi_exec($this->multiHandle, $active);
		}while ($mrc == CURLM_CALL_MULTI_PERFORM);
 
		while ($active && ($mrc == CURLM_OK)) {
			if (curl_multi_select($this->multiHandle) != -1) {
				do {
					$mrc = curl_multi_exec($this->multiHandle, $active);
					if($info = curl_multi_info_read($this->multiHandle)){					
						$chinfo = curl_getinfo($info['handle']);
						if ($info['msg'] == CURLMSG_DONE) {
							if($chinfo['http_code']==200){
								$key = array_search($chinfo['url'], $linkCollection);
								if($key){
									$htmlArray[$key] = curl_multi_getcontent($info['handle']);
								}
							}
						
							curl_multi_remove_handle($this->multiHandle, $info['handle']);
							curl_close($info['handle']);
						
							if (count($linkArray)) {
								$this->add_url_to_multi_handle(array_pop($linkArray));
								do {
									$mrc = curl_multi_exec($this->multiHandle, $active);
								} while ($mrc == CURLM_CALL_MULTI_PERFORM);
							}
						}
					}
				}while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		curl_multi_close($this->multiHandle);
		
		return $htmlArray;
	}
	
	private function add_url_to_multi_handle($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,  'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5'));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_multi_add_handle($this->multiHandle, $ch);
	}
}
?>
