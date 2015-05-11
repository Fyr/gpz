<?php

App::uses('AppController', 'Controller');

class SearchController extends AppController {
	public $name = 'Search';
	
	const MAX_ROW_SUGGEST = 100;
	const MAX_ROW_PRICE = 300;
	
	const SEARCH = 1;
	const PRICE = 2;
	
	private $key = 'EAAAAOInZ5vBwkgYdsvhjHvBppQYdUTeJ640oUJJzxCoE2vglu4v2Wm5xwo77ZCTSXvOHA==';
	
	private $curlObj;
	private $requestData;
	
	/*
	protected function _beforeInit() {
	    $this->components[] = 'RequestHandler';
	}
	*/
	
	public function index() {

		$this->type = self::SEARCH;
		
		$this->createRequest('http://www.zzap.ru/webservice/datasharing.asmx/GetSearchSuggest');
		$response = curl_exec($this->curlObj);
		$result = json_decode($response);
		
		$error = false;
		if(!$response or !isset($result->d)){
			$error = true;
		}
		$this->set('error', $error);
		$this->set('result',  json_decode($result->d,true));
		
	}
	
	public function price(){
		$this->autoRender = false;
		$this->type = self::PRICE;
		
		$this->createRequest('http://www.zzap.ru/webservice/datasharing.asmx/GetSearchResult');
		$response = curl_exec($this->curlObj);
		$result = json_decode($response);

		$errorText = 'Произошла ошибка';
		if(!$response or !isset($result->d)){
			echo json_encode(array('error'=>true));
			return;
		}
		$result = json_decode($result->d,true);
		if(!isset($result['table']) or $result['error']){
			echo json_encode(array('error'=>true));
			return; 
		}

		$price = $this->getPrice($result['table']);
		echo json_encode(array('error'=>false,'price'=>ceil($price*Configure::read('priceRatio'))));
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
	
	private function createRequest($link){
		
		$this->buildRequestData();
		$this->curlObj = curl_init($link);                                                                      
		curl_setopt($this->curlObj, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($this->curlObj, CURLOPT_POSTFIELDS,$this->requestData);                                                                  
		curl_setopt($this->curlObj, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($this->curlObj, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($this->requestData))                                                                       
		);                                                                                                                   
	}
	
	private function buildRequestData(){
		if($this->type == self::SEARCH){
			$dataArray = array('search_text'=>$this->request->data('search'),
							'row_count'=>  self::MAX_ROW_SUGGEST,
							'api_key'=>$this->key);
		}else if($this->type == self::PRICE){
			$dataArray = array('login'=>"",
								'password'=>"",
								'partnumber'=>$this->request->data('number'),
								'class_man'=>$this->request->data('class_man'),
								'location'=>"",
								'row_count'=>  self::MAX_ROW_PRICE,
								'api_key'=>$this->key);
		}
		
		$this->requestData = json_encode($dataArray);
	}
}
