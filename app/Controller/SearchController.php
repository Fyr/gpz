<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Vendor');
class SearchController extends AppController {
	public $components = array('Auth');
	public $name = 'Search';
	public $uses = array('ZzapApi', 'IpStats', 'IpLog');
	
	protected $CarSubtype, $CarSubsection;
	
	public function beforeFilter() {
		$this->Auth->allow(array('index', 'price'));
		parent::beforeFilter();
	}
	
	public function index() {
		$q = '';
		if (($carSubtype = $this->request->param('carSubtype')) && ($carSubsection = $this->request->param('slug'))) {
			$this->loadModel('CarSubtype');
			$this->loadModel('CarSubsection');
			
			$carSubtype = $this->CarSubtype->findBySlug($carSubtype);
			$carSubsection = $this->CarSubsection->findBySlug($carSubsection);
			if ($carSubtype && $carSubsection) {
				$q = $carSubtype['CarSubtype']['title'].' '.$carSubsection['CarSubsection']['title'];
				
				$this->seo = $carSubsection['Seo'];
				$this->set('article', Hash::merge($carSubsection, $carSubtype));
			}
		} else {
			$q = $this->request->query('q');
		}

		if (!$q){
			return $this->setError('Введите текст в строку поиска');
		}
			
		try {
			$this->setResult($this->ZzapApi->getSuggests($q));
		}  catch (Exception $e){
			// $this->setError($e->getMessage());
			$this->redirect($_SERVER['REQUEST_URI']);
		}		
	}
	
	public function price(){
		$lFullInfo = $this->Auth->loggedIn();
		
		$number = $this->request->query('number');
		$classman = $this->request->query('classman');
		
		if (!($number && $classman)) {
			return $this->setError('Неверный запрос');
		}
			
		try {	
			$this->setResult($this->ZzapApi->getItemInfo($classman, $number, $lFullInfo));
			$this->set('lFullInfo', $lFullInfo);
		}  catch (Exception $e){
			// $this->setError($e->getMessage());
			$this->redirect($_SERVER['REQUEST_URI']);
		}
	}
	
	private function setResult($result){
		$this->set('content', $result);
	}
	
	private function setError($errorText = 'Произошла ошибка'){
		$this->set('errorText', $errorText);
	}
	
}
