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
	
	public function view() {
		$brand = $this->request->param('brand');
		$slug = $this->request->param('slug');
		$article = $this->CarType->findBySlug($brand);
		if (!$article && !TEST_ENV) {
			return $this->redirect('/');
		}
		
		$conditions = array('CarSubtype.cat_id' => $article['CarType']['id']);
		$order = 'CarSubtype.title';
		
		if (!$slug) {
			$carSubtype = $this->CarSubtype->find('first', compact('conditions', 'order'));
			return $this->redirect(array(
				'controller' => 'Car', 'action' => 'view', 
				'brand' => $article['CarType']['slug'], 'slug' => $carSubtype['CarSubtype']['slug'], 'ext' => 'html'
			));
		}
		
		$carSubtype = $this->CarSubtype->findBySlug($slug);
		if (!$carSubtype && !TEST_ENV) {
			return $this->redirect('/');
		}
		
		$this->set('article', $article);
		
		$aCarSubtypes = $this->CarSubtype->find('all', compact('conditions', 'order'));
		$this->set('aCarSubtypes', $aCarSubtypes);

		$conditions = array('CarSubsection.cat_id' => $carSubtype['CarSubtype']['id']);
		$order = 'CarSubsection.title';
		$this->set('aCarSubsections', $this->CarSubsection->find('all', compact('conditions', 'order')));
		
		$this->seo = $article['Seo'];
	}
}
