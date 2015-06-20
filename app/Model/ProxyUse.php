<?php
App::uses('AppModel', 'Model');
class ProxyUse extends AppModel {
	
	public function getProxy($object_type) {
		$conditions = array('object_type' => $object_type, 'active' => 1);
		$order = array('used', 'modified');
		$row = $this->find('first', compact('conditions', 'order'));
		return $row;
	}
	
	public function useProxy($host, $method, $data = '') {
		$sql = sprintf('UPDATE %s SET used = used + 1, modified = "%s" WHERE host = "%s"', 
			$this->getTableName(), date('Y-m-d H:i:s'), $host);
		try {
			$this->trxBegin();
			
			$this->query($sql);
			// $this->initModel('ProxyLog')->clear();
			// $this->initModel('ProxyLog')->save(compact('host', 'method', 'data'));
			
			$this->trxCommit();
		} catch (Exception $e) {
			$this->trxRollback();
		}
	}
}
