<?php
App::uses('Model', 'Model');
class AppModel extends Model {
	
	protected $objectType = '', $altDbConfig = false;
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_beforeInit();
		parent::__construct($id, $table, $ds);
		$this->_afterInit();
	}
	
	protected function _beforeInit() {
		// Add here behaviours, models etc that will be also loaded while extending child class
		if ($this->altDbConfig) {
			if ($this->getDomain() !== $this->altDbConfig) {
				$this->useDbConfig = $this->altDbConfig;
			}
		}
	}

	protected function _afterInit() {
	    // after construct actions here
	}
	
	/**
	 * Auto-add object type in find conditions
	 *
	 * @param array $query
	 * @return array
	 */
	public function beforeFind($query) {
		if ($this->objectType) {
			$query['conditions'][$this->objectType.'.object_type'] = $this->objectType;
		}
		return $query;
	}
	
	/*
	public function loadModel($model) {
		// Проверить что лучше - loadModel или правильнее initModel
		App::import('Model', $model);
		list($plugin, $modelClass) = pluginSplit($model, true);
		$this->$model = new $modelClass();
		return $this->$model;
	}

	public function initModel($model, $id = null) {
		list($plugin, $modelClass) = pluginSplit($model, true);

		$modelClass = ClassRegistry::init(array(
			'class' => $plugin . $modelClass, 'alias' => $modelClass, 'id' => $id
		)); // $this->{$modelClass}
		if (!$modelClass) {
			throw new MissingModelException($modelClass);
		}
		return $modelClass;
	}
	*/
	
	public function loadModel($modelClass = null, $id = null) {
		list($plugin, $modelClass) = pluginSplit($modelClass, true);
		
		$this->{$modelClass} = ClassRegistry::init(array(
			'class' => $plugin . $modelClass, 'alias' => $modelClass, 'id' => $id
		));
		if (!$this->{$modelClass}) {
			throw new MissingModelException($modelClass);
		}
		
		return $this->{$modelClass};
	}
	
	private function _getObjectConditions($objectType = '', $objectID = '') {
		$conditions = array();
		if ($objectType) {
			$conditions[$this->alias.'.object_type'] = $objectType;
		}
		if ($objectID) {
			$conditions[$this->alias.'.object_id'] = $objectID;
		}
		return compact('conditions');
	}
	
	public function getObjectOptions($objectType = '', $objectID = '') {
		return $this->find('list', $this->_getObjectConditions($objectType, $objectID));
	}
	
	public function getObject($objectType = '', $objectID = '') {
		return $this->find('first', $this->_getObjectConditions($objectType, $objectID));
	}
	
	public function getObjectList($objectType = '', $objectID = '', $order = array()) {
		$conditions = array_values($this->_getObjectConditions($objectType, $objectID));
		return $this->find('all', compact('conditions', 'order'));
	}
	
	public function dateRange($field, $date1, $date2 = '') {
		// TODO: implement for free date2
		$date1 = date('Y-m-d 00:00:00', strtotime($date1));
		$date2 = date('Y-m-d 23:59:59', strtotime($date2));
		return array($field.' >= ' => $date1, $field.' <= ' => $date2);
	}
	
	public function dateTimeRange($field, $date1, $date2 = '') {
		// TODO: implement for free date2
		$date1 = date('Y-m-d H:i:s', strtotime($date1));
		$date2 = date('Y-m-d H:i:s', strtotime($date2));
		return array($field.' >= ' => $date1, $field.' <= ' => $date2);
	}
	
	public function trxBegin() {
		$this->getDataSource()->begin();
	}
	
	public function trxCommit() {
		$this->getDataSource()->commit();
	}
	
	public function trxRollback() {
		$this->getDataSource()->rollback();
	}
	
	public function getTableName() {
		return $this->getDataSource()->fullTableName($this);
	}
	
	public function setTableName($table) {
		$this->setSource($table);
	}
	
	public function isBot($ip = '') {
		if (!$ip) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$hostname = gethostbyaddr($ip);
		return ($hostname === 'spider-'.str_replace('.', '-', $ip).'.yandex.com') 
			|| ($hostname === 'crawl-'.str_replace('.', '-', $ip).'.googlebot.com');
	}

	public function getDomain() {
		list($domain) = explode('.', Configure::read('domain.url'));
		return $domain;
	}
}
