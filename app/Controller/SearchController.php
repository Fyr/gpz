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
	
	
	public function index() {

		$this->type = self::SEARCH;
		
		if(!isset($this->request->query['q']) or !$this->request->query['q']){
			$this->setError('Введите текст в строку поиска');
			return;
		}
		
		$this->createRequest('http://www.zzap.ru/webservice/datasharing.asmx/GetSearchSuggest');
		$response = curl_exec($this->curlObj);
		$result = json_decode($response);
		
		/*file_put_contents(WWW_ROOT.'api.log', date('d-m-Y H:i:s')." \n",FILE_APPEND);
		file_put_contents(WWW_ROOT.'api.log', "Data RECIEVE \n",FILE_APPEND);
		file_put_contents(WWW_ROOT.'api.log', $response,FILE_APPEND);*/
		
		if(!$response or !isset($result->d)){
			$this->setError('Ошибка запроса');
			return;
		}
		$this->setResult(json_decode($result->d,true));		
	}
	
	public function price(){
		$this->type = self::PRICE;
		
		if(!isset($this->request->query['number']) or !$this->request->query['number'] 
			or !isset($this->request->query['classman']) or !$this->request->query['classman']){
			$this->setError('Неверный запрос');
			return;
		}
		
		$this->createRequest('http://www.zzap.ru/webservice/datasharing.asmx/GetSearchResult');
		$response = curl_exec($this->curlObj);
		$result = json_decode($response);
		
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', date('d-m-Y H:i:s')." \n",FILE_APPEND);
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', "Data RECIEVE \n",FILE_APPEND);
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', $response."\n",FILE_APPEND);
		
		if(!$response or !isset($result->d)){
			$this->setError('Ошибка Запроса');
			return;
		}
		$content = json_decode($result->d,true);
		if(!isset($content['table']) or $content['error']){
			$this->setError('Неверный запрос');
			return; 
		}
		if(!$content['table']){
			$this->setError('Нет предложений');
			return;
		}
		
		$output['partnumber'] = $content['table'][0]['partnumber'];
		$output['class_man'] = $content['table'][0]['class_man'];
		$output['class_cat'] = $content['table'][0]['class_cat'];
		$output['imagepath'] = $content['table'][0]['imagepath'];
		$output['price'] = $this->getPrice($content['table']);
		$this->setResult($output);
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
		
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', date('d-m-Y H:i:s')." \n",FILE_APPEND);
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', "Data SENT \n",FILE_APPEND);
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', "$link \n",FILE_APPEND);
		file_put_contents(ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'api.log', $this->requestData,FILE_APPEND);
	}
	
	private function buildRequestData(){
		if($this->type == self::SEARCH){
			$dataArray = array('search_text'=>$this->request->query['q'],
							'row_count'=>  self::MAX_ROW_SUGGEST,
							'api_key'=>$this->key);
		}else if($this->type == self::PRICE){
			$dataArray = array('login'=>"",
								'password'=>"",
								'partnumber'=>$this->request->query['number'],
								'class_man'=>$this->request->query['classman'],
								'location'=>"",
								'row_count'=>  self::MAX_ROW_PRICE,
								'api_key'=>$this->key);
		}
		
		$this->requestData = json_encode($dataArray);
	}
	
	private function setResult($result){
		$this->set('output',array('result'=>true,'content'=>$result));
	}
	
	private function setError($errorText = 'Произошла ошибка'){
		$this->set('output',array('result'=>false,'errorText'=>$errorText));
	}
	
}
