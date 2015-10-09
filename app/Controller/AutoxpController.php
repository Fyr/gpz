<?php
App::uses('AppController', 'Controller');
App::uses('AutoxpApi', 'Model');
App::uses('AutoxpRouter', 'Vendor');
class AutoxpController extends AppController {
	public $name = 'Autoxp';
	public $uses = array('AutoxpApi');
	public $helpers = array('ObjectType', 'Autoxp');
	
	protected $Subsection;
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->currMenu = 'Products';
	}
	
	public function beforeRender() {
		$this->set('searchID', $this->AutoxpApi->getSearchID());
		parent::beforeRender();
	}
	
	public function redirect($url, $status = 302) {
		return parent::redirect(AutoxpRouter::url($url), $status);
	}
	
	public function index($brand = '') {
		$brands = $this->AutoxpApi->getMarks();
		$this->set('aCatalog', $brands);
		
		if ($brand) {
			$brand = strtoupper($brand);
			$brands = Hash::combine($brands, '{n}.title', '{n}.id');
			if (isset($brands[$brand]) && $brands[$brand]) {
				$this->redirect(array('action' => 'brand', $brands[$brand]));
			}
		}
	}
	
	public function brand($mark_id) {
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = $this->AutoxpApi->getModels($mark_id);
		$this->set('aModels', $aModels);
		unset($aModels);
		
		$title = $mark['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function model($mark_id, $model_id) {
		$model_id = str_replace('|', '/', $model_id);
		
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->AutoxpApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aBodyTypes = $this->AutoxpApi->getBodyTypes($mark_id, $model_id);
		$this->set('aBodyTypes', $aBodyTypes);
		
		$title = $mark['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function bodytype($mark_id, $model_id, $body_type) {
		$model_id = str_replace('|', '/', $model_id);
		
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->AutoxpApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aBodyTypes = Hash::combine($this->AutoxpApi->getBodyTypes($mark_id, $model_id), '{n}.id', '{n}');
		$body = $aBodyTypes[$body_type];
		$this->set('body', $body);
		unset($aBodyTypes);
		
		$aMotors = $this->AutoxpApi->getMotors($mark_id, $model_id, $body_type);
		$this->set('aMotors', $aMotors);
		
		$title = $mark['title'].' '.$model['title'].' '.$body['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function motor($mark_id, $model_id, $body_type, $fuel_id) {
		$model_id = str_replace('|', '/', $model_id);
		
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->AutoxpApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aBodyTypes = Hash::combine($this->AutoxpApi->getBodyTypes($mark_id, $model_id), '{n}.id', '{n}');
		$body = $aBodyTypes[$body_type];
		$this->set('body', $body);
		unset($aBodyTypes);
		
		$aMotors = Hash::combine($this->AutoxpApi->getMotors($mark_id, $model_id, $body_type), '{n}.id', '{n}');
		$fuel = $aMotors[$fuel_id];
		$this->set('fuel', $fuel);
		unset($aMotors);
		
		$aModelsInfo = $this->AutoxpApi->getModelsInfo($mark_id, $model_id, $body_type, $fuel_id);
		$this->set('aModelsInfo', $aModelsInfo);
		
		$title = $mark['title'].' '.$model['title'].' '.$body['title'].' '.$fuel['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function sections($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel) {
		$model_id = str_replace('|', '/', $model_id);
		$hash = urldecode($hash);
		$this->set('hash', $hash);
		$this->set('submodel', $submodel);
		// $cache_params = compact('mark_id', 'model_id', 'body_type', 'fuel_id', 'submodel');
		
		try {
			$aSubsections = $this->AutoxpApi->getModelSections($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel);
			$this->set('aSubsections', $aSubsections);
		} catch (Exception $e) {
			$this->redirect(array('action' => 'motor', $mark_id, $model_id, $body_type, $fuel_id));
			return;
		}
		
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->AutoxpApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aBodyTypes = Hash::combine($this->AutoxpApi->getBodyTypes($mark_id, $model_id), '{n}.id', '{n}');
		$body = $aBodyTypes[$body_type];
		$this->set('body', $body);
		unset($aBodyTypes);
		
		$aMotors = Hash::combine($this->AutoxpApi->getMotors($mark_id, $model_id, $body_type), '{n}.id', '{n}');
		$fuel = $aMotors[$fuel_id];
		$this->set('fuel', $fuel);
		unset($aMotors);
		
		$title = $mark['title'].' '.$model['title'].' '.$body['title'].' '.$fuel['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
		
		// Сохраняем осн.узлы для того, чтобы навесить картинки
		// обьединять секции TecDoc и AutoXP мы к сож-ю можем только по названию
		$this->loadModel('Subsection');
		$aTitles = Hash::extract($aSubsections, '{n}.title');
		$conditions = array('title' => $aTitles);
		$subsections = $this->Subsection->find('all', compact('conditions'));
		$subsections = Hash::combine($subsections, '{n}.Subsection.title', '{n}');
		foreach($aSubsections as $row) {
			if (!isset($subsections[$row['title']])) {
				$this->Subsection->clear();
				$this->Subsection->save(array('title' => $row['title'], 'td_id' => 0));
			} else {
				$subsections[$row['title']]['AutoXP'] = $row;
			}
		}
		$this->set('subsections', $subsections);
	}
	
	public function subsections($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel, $grnum) {
		$model_id = str_replace('|', '/', $model_id);
		$hash = urldecode($hash);
		$this->set('hash', $hash);
		$this->set('submodel', $submodel);
		// $cache_params = compact('mark_id', 'model_id', 'body_type', 'fuel_id', 'submodel', 'grnum');
		
		try {
			$aSubsections = $this->AutoxpApi->getModelSubsections($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel, $grnum);
			$this->set('aSubsections', $aSubsections);
		} catch (Exception $e) {
			$this->redirect(array('action' => 'motor', $mark_id, $model_id, $body_type, $fuel_id));
			return;
		}
		
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->AutoxpApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aBodyTypes = Hash::combine($this->AutoxpApi->getBodyTypes($mark_id, $model_id), '{n}.id', '{n}');
		$body = $aBodyTypes[$body_type];
		$this->set('body', $body);
		unset($aBodyTypes);
		
		$aMotors = Hash::combine($this->AutoxpApi->getMotors($mark_id, $model_id, $body_type), '{n}.id', '{n}');
		$fuel = $aMotors[$fuel_id];
		$this->set('fuel', $fuel);
		unset($aMotors);
		
		$title = $mark['title'].' '.$model['title'].' '.$body['title'].' '.$fuel['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}

	public function autoparts($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel, $grnum, $pdgrnum) {
		$model_id = str_replace('|', '/', $model_id);
		$hash = urldecode($hash);
		$this->set('hash', $hash);
		$this->set('submodel', $submodel);
		// $cache_params = compact('mark_id', 'model_id', 'body_type', 'fuel_id', 'submodel', 'grnum', 'pdgrnum');
		try {
			$aAutoparts = $this->AutoxpApi->getAutoparts($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel, $grnum, $pdgrnum);
			$this->set('aAutoparts', $aAutoparts);
		} catch (Exception $e) {
			$this->redirect(array('action' => 'motor', $mark_id, $model_id, $body_type, $fuel_id));
			return;
		}
		
		$aCatalog = Hash::combine($this->AutoxpApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->AutoxpApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aBodyTypes = Hash::combine($this->AutoxpApi->getBodyTypes($mark_id, $model_id), '{n}.id', '{n}');
		$body = $aBodyTypes[$body_type];
		$this->set('body', $body);
		unset($aBodyTypes);
		
		$aMotors = Hash::combine($this->AutoxpApi->getMotors($mark_id, $model_id, $body_type), '{n}.id', '{n}');
		$fuel = $aMotors[$fuel_id];
		$this->set('fuel', $fuel);
		unset($aMotors);
		
		$cache_params = compact('mark_id', 'model_id', 'body_type', 'fuel_id', 'submodel', 'grnum');
		$aSubsections = $this->AutoxpApi->getModelSubsections($mark_id, $model_id, $body_type, $fuel_id, $hash, $submodel, $grnum);
		$aSubsections = Hash::combine($aSubsections, '{n}.pdgrnum', '{n}');
		$this->set('subsection', $aSubsections[$pdgrnum]);
		unset($aSubsections);
		
		$title = $mark['title'].' '.$model['title'].' '.$body['title'].' '.$fuel['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function search() {
		$searchID = $this->request->query('ses');
		$vin = $this->request->query('vin');
		$aSearch = $this->AutoxpApi->searchVIN($searchID, $vin);
		$this->set('aSearch', $aSearch);
	}
	
	public function searchSections($mark_id, $hash) {
		$this->autoRender = false;
		$hash = str_replace('|', '/', urldecode($hash));
		try {
			$a = $this->AutoxpApi->searchSections($mark_id, $hash);
			$this->redirect(array('action' => 'sections', $a['mark'], $a['model'], $a['body_type'], $a['fuel'], $a['hash']));
		} catch (Exception $e) {
			$this->redirect(array('brand' => $mark_id));
		}
	}
}
