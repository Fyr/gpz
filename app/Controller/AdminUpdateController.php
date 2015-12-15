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
	/*
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
	*/
	
	public function proxy() {
		$this->autoRender = false;
		$this->ProxyUse = $this->loadModel('ProxyUse');
		
		try {
			$this->ProxyUse->trxBegin();
			
			$this->ProxyUse->query('UPDATE proxy_uses SET active = 0');
			
			$text = 'IP1: ru1.proxik.net
порт: 80
IP2: ru2.proxik.net
порт: 8080
IP3: ru3.proxik.net
порт: 8080
IP4: ru4.proxik.net
порт: 8080
IP5: ru5.proxik.net
порт: 8080
логин: fyr51412
пароль: Ic6J3kA1c7Ys';
			$aProxy = $this->_getProxyInfo($text, 'Site');
			$this->ProxyUse->clear();
			$this->ProxyUse->saveAll($aProxy);
			fdebug($aProxy);
		
			$text = 'IP1: usa1.proxik.net
порт: 80
IP2: usa2.proxik.net
порт: 80
IP3: usa3.proxik.net
порт: 80
IP4: usa4.proxik.net
порт: 80
IP5: usa5.proxik.net
порт: 80
логин: fyr51412
пароль: Ic6J3kA1c7Ys';
			$aProxy = $this->_getProxyInfo($text, 'Bot');
			$this->ProxyUse->clear();
			$this->ProxyUse->saveAll($aProxy);
			fdebug($aProxy);
		
			$text = 'IP1: de1.proxik.net
порт: 8080
IP2: de2.proxik.net
порт: 8080
IP3: de3.proxik.net
порт: 8080
IP4: de4.proxik.net
порт: 8080
IP5: de5.proxik.net
порт: 8888
логин: fyr51412
пароль: Ic6J3kA1c7Ys';
			$aProxy = $this->_getProxyInfo($text, 'Bot');
			$this->ProxyUse->clear();
			$this->ProxyUse->saveAll($aProxy);
			fdebug($aProxy);
			
			$this->ProxyUse->trxCommit();
			
			echo 'SUCCESS';
		} catch (Exception $e) {
			echo 'Error!';
			$this->ProxyUse->trxRollback();
			echo $e->getMessage();
		}
	}
	
	private function _getProxyInfo($text, $object_type) {
		$aStr = explode('|', str_replace(array("\r\n", "\r", "\n"), '|', $text));
		$aData = array();
		foreach($aStr as $str) {
			list($title, $data) = explode(': ', $str);
			$aData[] = trim($data);
		}
		if (count($aData) < 12) {
			throw new Exception('Error parsing data!'.print_r($aData, true));
		}
		
		$login = $aData[10];
		$psw = $aData[11];
		unset($aData[10]);
		unset($aData[11]);
		$proxy = array();
		for($i = 0; $i < 10; $i++) {
			$data = array('host' => $aData[$i]);
			$i++;
			$data['host'].= ':'.$aData[$i];
			$data['login'] = $login;
			$data['password'] = $psw;
			$data['object_type'] = $object_type;
			$data['active'] = 1;
			
			foreach($data as $key => $val) {
				if (!$val) {
					throw new Exception("Error with `{$key}`<br/>".print_r($data, true));
				}
			}
			
			$proxy[] = $data;
		}
		return $proxy;
	}

}

function sdebug($data) {
	echo '<pre>'.print_r($data, true).'</pre>';
}
