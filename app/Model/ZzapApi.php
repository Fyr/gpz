<?php
App::uses('AppModel', 'Model');

class ZzapApi extends AppModel {
	
	public $useTable = false;
	
	const MAX_ROW_SUGGEST = 100;
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
		$content = $this->sendApiRequest('GetSearchSuggest', $dataArray);
		return $content;
	}
	
	public function getResults($classman, $partnumber){
		
		$dataArray = array(
			'login' => '',
			'password'=> '',
			'partnumber' => $partnumber,
			'class_man' => $classman,
			'location' => '',
			'row_count'=> self::MAX_ROW_PRICE
		);
		
		$content = $this->sendApiRequest('GetSearchResult', $dataArray);
		
		if(!$content['table']){
			throw new Exception('Нет предложений');
		}
		
		$output['partnumber'] = $content['table'][0]['partnumber'];
		$output['class_man'] = $content['table'][0]['class_man'];
		$output['class_cat'] = $content['table'][0]['class_cat'];
		$output['imagepath'] = $content['table'][0]['imagepath'];
		$output['price'] = $this->getPrice($content['table']);
		
		return $output;
		
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
		return min($prices);
	}
	
	private function writeLog($actionType, $data){
		$string = date('d-m-Y H:i:s').' '.$actionType.' '.$data;
		file_put_contents(Configure::read('ZzapApi.log'), $string."\r\n", FILE_APPEND);
	}
}