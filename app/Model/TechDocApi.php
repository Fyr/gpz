<?php
App::uses('AppModel', 'Model');
App::uses('Curl', 'Vendor');

class TechDocApi extends AppModel {
	public $useTable = false;
	
	private function writeLog($actionType, $data = ''){
		$string = date('d-m-Y H:i:s').' '.$actionType.' '.$data;
		file_put_contents(Configure::read('TechDocApi.log'), $string."\r\n", FILE_APPEND);
	}
	
	private function sendRequest($method, $data = array()) {
		$data['method'] = $method;
		$cache_key = http_build_query($data); // to cache all params except api_key
		$data['key'] = Configure::read('TechDocApi.key');
		
		$response = Cache::read($cache_key, 'techdoc');
		if ($response) {
			return $response;
		}
		
		$url = Configure::read('TechDocApi.url').'?'.http_build_query($data);
		$curl = new Curl($url);
		$curl->setOption(CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) 2016");
		$curl->setParam('_server', json_encode($_SERVER));
		
		$this->writeLog('REQUEST', 'URL: '.$url.' DATA: '.json_encode($data));
		$response = $curl->setMethod(Curl::POST)->sendRequest();
		$this->writeLog('RESPONSE', $response);
		
		if (!trim($response)) {
			throw new Exception('TechDoc API: No response from server');
		}
		
		$response = json_decode($response, true);
		if (isset($response['error']) && $response['error']) {
			throw new Exception($response['error']);
		}
		if (!$response || !is_array($response)) {
			throw new Exception('TechDoc API: Bad response from server');
		}
		
		Cache::write($cache_key, $response, 'techdoc');
		return $response;
	}
	
	public function getMarks() {
		$response = $this->sendRequest('marks');
		$response = array_map(array($this, 'processMarks'), $response);
		return $response;
	}
	
	public function processMarks($e) {
		return array('Brand' => array('id' => $e['id'], 'title' => $e['mark']));
	}
	
	public function getModels($mark_id) {
		$response = $this->sendRequest('models', compact('mark_id'));
		
		$aModels = array();
		foreach($response as $row) {
			$aModels[$row['model']][] = $row;
		}
		return $aModels;
	}

	public function getModelSections($mark_id, $model_id) {
		$response = $this->sendRequest('types', compact('mark_id', 'model_id'));
		return $response;
	}
	
	public function getModelSubsections($mark_id, $model_id, $type_id) {
		$node_id = 0;
		$response = $this->sendRequest('searchtree', compact('mark_id', 'model_id', 'type_id', 'node_id'));
		return $response;
	}
	
	public function getAutoparts($mark_id, $model_id, $type_id, $node_id) {
		$response = $this->sendRequest('searchtree_data', compact('mark_id', 'model_id', 'type_id', 'node_id'));
		return $response;
	}
}
