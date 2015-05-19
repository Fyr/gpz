<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('CarType', 'Model');
App::uses('CarSubtype', 'Model');
App::uses('CarSubsection', 'Model');

class CarController extends AppController {
	public $name = 'Car';
	public $uses = array('CarType', 'CarSubtype', 'CarSubsection');
	public $helpers = array('ObjectType');
	
	public function view($slug) {
		if (is_numeric($slug)) {
			$article = $this->CarType->findById($slug);
		} else {
			$article = $this->CarType->findBySlug($slug);
		}
		
		if (!$article && !TEST_ENV) {
			return $this->redirect('/');
		}
		
		$this->set('article', $article);
		$order = 'CarSubtype.title';
		$conditions = array('CarSubtype.cat_id' => $article['CarType']['id']);
		$aCarSubtypes = $this->CarSubtype->find('all', compact('conditions', 'order'));
		$this->set('aCarSubtypes', $aCarSubtypes);
		$this->set('aCarSubsections', $this->CarSubsection->find('all', array('order' => 'CarSubsection.title')));
		
		$this->seo = $article['Seo'];
	}
}
