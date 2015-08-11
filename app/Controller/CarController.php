<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('CarType', 'Model');
App::uses('CarSubtype', 'Model');
App::uses('CarSubsection', 'Model');
App::uses('SiteRouter', 'Vendor');

class CarController extends AppController {
	public $name = 'Car';
	public $uses = array('CarType', 'CarSubtype', 'CarSubsection');
	public $helpers = array('ObjectType');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->currMenu = 'Products';
	}
	
	public function index() {
		$aCarTypes = $this->CarType->find('all');
		$this->set('aCarTypes', $aCarTypes);
	}
	
	public function viewCarType() {
		$carType = $this->CarType->findBySlug($this->request->param('carType'));
		if (!$carType && !TEST_ENV) {
			return $this->redirect('/');
		}
		
		$conditions = array('CarSubtype.cat_id' => $carType['CarType']['id']);
		$order = 'CarSubtype.title';
		$carSubtype = $this->CarSubtype->find('first', compact('conditions', 'order'));
		
		if ($carSubtype) {
			return $this->redirect(SiteRouter::url($carSubtype));
		}
		
		$this->set('article', $carType);
		$this->seo = $carType['Seo'];
	}
	
	public function view() {
		$carType = $this->CarType->findBySlug($this->request->param('carType'));
		$carSubtype = $this->CarSubtype->findBySlug($this->request->param('carSubtype'));
		if (!($carType && $carSubtype) &&!TEST_ENV) {
			return $this->redirect('/');
		}
		$this->set('carType', $carType);
		$this->set('carSubtype', $carSubtype);
		
		$conditions = array('CarSubtype.cat_id' => $carSubtype['CarType']['id']);
		$order = 'CarSubtype.title';
		$aCarSubtypes = $this->CarSubtype->find('all', compact('conditions', 'order'));
		$this->set('aCarSubtypes', $aCarSubtypes);

		$conditions = array('CarSubsection.cat_id' => $carSubtype['CarSubtype']['id']);
		$order = 'CarSubsection.title';
		$this->set('aCarSubsections', $this->CarSubsection->find('all', compact('conditions', 'order')));
		
		$article = $carSubtype;
		$this->seo = $carSubtype['Seo'];
		/*
		if (mb_strlen($carSubtype['CarSubtype']['body'], 'utf8') < 10) {
			$article = $carType;
			$this->seo = $carType['Seo'];
		}
		*/
		$this->set('article', $article);
	}
}
