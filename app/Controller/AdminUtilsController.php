<?php
App::uses('AdminController', 'Controller');
class AdminUtilsController extends AdminController {
    public $name = 'AdminUtils';
    public $uses = array('ZzapLog');
    
    public function beforeFilter() {
		if (!$this->isAdmin()) {
			$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
			return;
		}
		parent::beforeFilter();
	}
	
    public function index() {
    }
    
    public function cleanImageCache() {
    	set_time_limit(600);
    	$this->autoRender = false;
    	
    	$this->loadModel('Media.Media');
    	$page = 1;
    	$limit = 10;
    	$order = 'Media.id';
    	$count = 0;
    	while ($aMedia = $this->Media->find('all', compact('page', 'limit', 'order'))) {
    		$page++;
    		foreach($aMedia as $media) {
    			$count++;
    			$this->Media->cleanCache($media['Media']['id']);
			}
    	}
    	echo 'Processed '.$count.' media files';
    }
    
    public function ipstats() {
    	$fields = array('ip', 'host', 'COUNT(*) AS count');
    	$group = 'ip';
    	$order = 'count DESC';
    	$rows = $this->ZzapLog->find('all', compact('fields', 'group', 'order'));
    	// SELECT ip, host, COUNT(*) AS count FROM `zzap_logs` GROUP BY ip ORDER BY count DESC
    	$this->set('rows', $rows);
    }
}
