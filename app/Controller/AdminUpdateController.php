<?php
App::uses('AdminController', 'Controller');
class AdminUpdateController extends AdminController {
    public $name = 'AdminUtils';
    public $layout = false;
    
    public function beforeFilter() {
		if (!$this->isAdmin()) {
			$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
			return;
		}
		parent::beforeFilter();
		$this->autoRender = false;
	}
	
	public function update() {
		set_time_limit(600);
		$this->loadModel('Subsection');
		$this->loadModel('CacheTechdoc');
		$this->CacheTechdoc->useTable = 'cache_techdoc';
		
		$conditions = array('CacheTechdoc.key LIKE "method=searchtree%node_id=0"');
		$page = 1;
		$limit = 100;
		$count = 0;
		while ($aRows = $this->CacheTechdoc->find('all', compact('conditions', 'page', 'limit'))) {
			$page++;
			foreach($aRows as $cache) {
				$aSubsections = unserialize($cache['CacheTechdoc']['value']);
				$this->Subsection->saveMainSubsections($aSubsections);
				$count++;
			}
		}
		echo 'Processed '.$count.' rec(s)';
	}

}
