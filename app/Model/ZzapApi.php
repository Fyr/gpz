<?php
App::uses('AppModel', 'Model');

class ZzapApi extends AppModel {
	
	public $useTable = false;
	
	const MAX_ROW_SUGGEST = 10;
	const MAX_ROW_PRICE = 300;
	
	private function createRequest($link, $data){
		
		$curl = curl_init($link);                                                                      
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data))                                                                       
		);  
		return $curl;
	}
	
	
	private function sendApiRequest($method, $data){
		
		$url = Configure::read('ZzapApi.url').$method;
		
		$data['api_key'] = Configure::read('ZzapApi.key');
		$data = json_encode($data);
		$this->writeLog('REQUEST', "URL: {$url}; DATA: {$data}");
		$response = curl_exec($this->createRequest($url, $data));
		$this->writeLog('RESPONSE', $response);
		
		$result = json_decode($response);
		if(!$response or !isset($result->d)){
			throw new Exception(__('API Server error'));
		}
		
		$content = json_decode($result->d,true);
		
		if (!isset($content['table']) || $content['error']){
			throw new Exception(__('API Server response error: %s', $content['error'])); 
		}
		
		return $content;
	}
	
	public function getSuggests($searchString) {
		
		$dataArray = array(
			'search_text' => $searchString,
			'row_count'=>  self::MAX_ROW_SUGGEST
		);
		$this->content = $this->sendApiRequest('GetSearchSuggest', $dataArray);

		if($this->content['table']){
			//ограничение в АПИ не работает - поэтому срезаем
			$this->content['table'] = array_slice($this->content['table'], 0, self::MAX_ROW_SUGGEST);
			$this->multiCurlPrice($this->content['table']);
			$this->content['table'] = Hash::sort($this->content['table'], '{n}.price', 'desc'); 
		}
		return $this->content;
	}
	
	private function getSuggestPrice($priceResponse){
		
		if(!$priceResponse){
			return 0; 
		}
		
		$priceContent = json_decode($priceResponse);
		if(!$priceContent or !isset($priceContent->d)){
			return 0;
		}
		
		$priceResult = json_decode($priceContent->d,true);	
		if(!isset($priceResult['table']) or !$priceResult['table']){
			return 0;
		}
		
		return $this->getPrice($priceResult['table']);
		
	}
	
	private function getPrice($priceResult){
		$prices = array();
		foreach ($priceResult as $priceRow){
			$priceRow['price'] = preg_replace('/\D/', '', $priceRow['price']);
			$priceRow['qty'] = preg_replace('/\D/', '', $priceRow['qty']);
			
			if($priceRow['price']>0 and $priceRow['qty']>0){
				$prices[] = $priceRow['price'];
			}
		}
		if(!$prices){
			return 0;
		}
		//переводим процент в десятичный коэффициент
		$priceRatio = 1+(Configure::read('Settings.price_ratio')/100);
		return round($priceRatio * Configure::read('Settings.xchg_rate') * min($prices),-2);
	}
	
	private function writeLog($actionType, $data){
		$string = date('d-m-Y H:i:s').' '.$actionType.' '.$data;
		file_put_contents(Configure::read('ZzapApi.log'), $string."\r\n", FILE_APPEND);
	}
	
	private function getRequestPriceBody($classman,$partnumber){
		$dataArray = array(
			'login' => '',
			'password'=> '',
			'partnumber' => $partnumber,
			'class_man' => $classman,
			'location' => '',
			'row_count' => self::MAX_ROW_PRICE,
			'api_key' => Configure::read('ZzapApi.key')
		);
		return json_encode($dataArray);
	}


	private function multiCurlPrice($suggestTable){

		$url = Configure::read('ZzapApi.url').'GetSearchResult';
		$multi = curl_multi_init();
		$channels = array();
		
		foreach ($suggestTable as $id=>$suggest) {
			$data[$id] = $this->getRequestPriceBody($suggest['class_man'], $suggest['partnumber']);
			$curl = curl_init();  
			curl_setopt($curl, CURLOPT_URL, $url );
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data[$id]);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                      
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
				'Content-Type: application/json',                                                                                
				'Content-Length: ' . strlen($data[$id]))                                                                       
			);
 
			curl_multi_add_handle($multi, $curl);
			$channels[$id] = $curl;
		}
		
		$active = null;

		do {
			$mrc = curl_multi_exec($multi, $active);
		}while ($mrc == CURLM_CALL_MULTI_PERFORM);
 
		while ($active && ($mrc == CURLM_OK)) {
			if (curl_multi_select($multi) != -1) {
				do {
					$mrc = curl_multi_exec($multi, $active);
					$info = curl_multi_info_read($multi);
					if ($info['msg'] == CURLMSG_DONE) {
						$ch = $info['handle'];
						$id = array_search($ch, $channels);
						$this->writeLog('REQUEST', "URL: {$url}; DATA: {$data[$id]}");
						$priceContent = curl_multi_getcontent($ch);
						$this->writeLog('RESPONSE', $priceContent);
						$this->content['table'][$id]['price'] = $this->getSuggestPrice($priceContent);
						curl_multi_remove_handle($multi, $ch);
						curl_close($ch);
					}
				}
				while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		curl_multi_close($multi);
	}
	
	public function getItemPrice($classman,$partnumber){
		$data = array(
			'login' => '',
			'password'=> '',
			'location' => '',
			'class_man' => $classman,
			'partnumber' => $partnumber,
			'row_count'=>  self::MAX_ROW_PRICE
		);
		$content = $this->sendApiRequest('GetSearchResult', $data);
		if(!$content['table']){
			throw new Exception(__('API Server response error:'));
		}
		$output['class_cat'] = $content['table'][0]['class_cat'];
		$output['class_man'] = $classman;
		$output['partnumber'] = $partnumber;
		$output['imagepath'] = $content['table'][0]['imagepath'];
		$output['shipping'] = $content['table'][0]['descr_qty'];
		$output['price'] = $this->getPrice($content['table']);
		return $output;
		
	}
}