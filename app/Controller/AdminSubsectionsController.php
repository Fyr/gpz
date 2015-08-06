<?php
App::uses('AdminController', 'Controller');
class AdminSubsectionsController extends AdminController {
    public $name = 'AdminSubsections';
    public $uses = array('Subsection');
    
    public function index() {
    	$this->paginate['Subsection'] = array(
    		'fields' => array('title')
    	);
    	$this->PCTableGrid->paginate('Subsection');
    	$this->currMenu = 'Catalog';
    }
    
    public function edit($id = 0) {
    	if ($this->request->is(array('put', 'post'))) {
    		$this->Subsection->save($this->request->data);
    		$id = $this->Subsection->id;
			$baseRoute = array('action' => 'index');
			return $this->redirect(($this->request->data('apply')) ? $baseRoute : array($id));
    	} else {
    		$this->request->data = $this->Subsection->findById($id);
    	}
    	
    	$this->currMenu = 'Catalog';
    }
}
