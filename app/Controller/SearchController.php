<?php

App::uses('AppController', 'Controller');

class SearchController extends AppController {
	public $name = 'Search';
	public $uses = array('ZzapApi');
	
	public function index() {
		
		if(!isset($this->request->query['q']) or !$this->request->query['q']){
			$this->setError('Введите текст в строку поиска');
			return;
		}
		try{
			$result = $this->ZzapApi->getSuggests($this->request->query['q']);
		}  catch (Exception $e){
			$this->setError($e->getMessage());
			return;
		}		
		$this->setResult($result);		
	}
	
	public function price(){
		
		if(!isset($this->request->query['number']) or !$this->request->query['number'] 
			or !isset($this->request->query['classman']) or !$this->request->query['classman']){
			$this->setError('Неверный запрос');
			return;
		}
		
		try{
			$result = $this->ZzapApi->getResults($this->request->query['classman'],$this->request->query['number']);
		}  catch (Exception $e){
			$this->setError($e->getMessage());
			return;
		}
		
		$this->setResult($result);
	}
	
	private function setResult($result){
		$this->set('output',array('result'=>true,'content'=>$result));
	}
	
	private function setError($errorText = 'Произошла ошибка'){
		$this->set('output',array('result'=>false,'errorText'=>$errorText));
	}
	
}
