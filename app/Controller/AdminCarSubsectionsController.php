<?php
App::uses('AdminController', 'Controller');
class AdminCarSubsectionsController extends AdminController {
    public $name = 'AdminCarSubsections';
    public $components = array('Article.PCArticle');
    public $uses = array('CarSubsection', 'CarSubtype');
    public $helpers = array('ObjectType');
    
    public function index($objectID = '') {
        $this->paginate = array(
        	'CarSubsection' => array(
        		'conditions' => array('CarSubsection.cat_id' => $objectID),
            	'fields' => array('id', 'title', 'sorting'),
            	'order' => array('CarSubsection.sorting' => 'ASC')
            ),
        );
        
        $this->PCTableGrid->paginate('CarSubsection');
        // $aRowset = $this->PCArticle->setModel($objectType)->index();
        $this->set('objectID', $objectID);
        // $this->set('aRowset', $aRowset);
    	$this->set('carSubtype', $this->CarSubtype->findById($objectID));
    	$this->currMenu = 'Catalog';
    }
    
	public function edit($id = 0, $objectID = '') {
		$this->loadModel('Media.Media');
		$objectType = 'CarSubsection';
		$this->set('objectType', $objectType);
		
		if (!$id) {
			// если не задан ID, то objectType+ObjectID должны передаваться
			$this->request->data('Seo.object_type', $objectType);
			$this->request->data('CarSubsection.cat_id', $objectID);
		}
		
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CarSubsection->saveAll($this->request->data)) {
				if (!$id) {
					$id = $this->CarSubsection->id;
				}
				$indexRoute = array('action' => 'index', $objectID);
				$editRoute = array('action' => 'edit', $id, $objectID);
				return $this->redirect(($this->request->data('apply')) ? $indexRoute : $editRoute);
			}
		} else {
			$this->request->data = $this->CarSubsection->findById($id);
		}
		
		$this->set('carSubtype', $this->CarSubtype->findById($objectID));
		
		if (!$this->request->data('CarSubsection.sorting')) {
			$this->request->data('CarSubsection.sorting', '0');
		}
		
       	$this->currMenu = 'Catalog';
	}
}
