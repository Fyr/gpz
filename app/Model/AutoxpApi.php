<?php
App::uses('AppModel', 'Model');
App::uses('ProxyUse', 'Model');
App::uses('LogAutoxp', 'Model');
App::uses('Curl', 'Vendor');
App::import('Vendor', 'simple_html_dom');

class AutoxpApi extends AppModel {
	public $useTable = false;
	
	private $searchID;
	private $staticMethods = array('getMarks', 'getModels', 'getBodyTypes', 'getMotors');
	// private $proxy_used = '', $curlStatus = ''; // для логов
	
	private function writeLog($actionType, $data = '') {
		if (Configure::read('AutoxpApi.txtLog')) {
			$string = date('d-m-Y H:i:s') . ' ' . $actionType . ' ' . $data;
			file_put_contents(Configure::read('AutoxpApi.log'), $string . "\r\n", FILE_APPEND);
		}
	}
	
	private function writeDBLog($method, $requestData, $responseType, $_response = '', $proxy_used = '', $curlStatus = '') {
		if (Configure::read('AutoxpApi.dbLog')) {
			$this->loadModel('LogAutoxp')->clear();

			$ip = $_SERVER['REMOTE_ADDR'];
			$proxy_type = ($this->isBot($ip)) ? 'Bot' : 'Site';

			$this->loadModel('LogAutoxp')->save(array(
				'ip_type' => $proxy_type,
				'ip' => $ip,
				'host' => gethostbyaddr($ip),
				'ip_details' => json_encode($_SERVER),
				'proxy_used' => $proxy_used,
				'method' => $method,
				'request' => $this->getRequestURL($requestData, $method),
				'response_type' => $responseType,
				'response_status' => ($curlStatus) ? json_encode($curlStatus) : '',
				'response' => $_response,
				'cache_id' => ($responseType == 'CACHE') ? Hash::get(Cache::settings('autoxp'), 'data.id') : 0,
				'cache' => ($responseType == 'CACHE') ? Hash::get(Cache::settings('autoxp'), 'data.value') : ''
			));
		}
	}
	
	private function getRequestURL($data, $method = '') {
		if ($method == 'getModelSections') {
			$data = array('mark' => $data['mark'], 'codaxp' => $data['codaxp']);
			$xparams = '&'.http_build_query($data);
		} elseif ($method == 'getModelSubsections') {
			$data = array('mark' => $data['mark'], 'codaxp' => $data['codaxp'], 'grnum' => $data['grnum']);
			$xparams = '&'.http_build_query($data);
		} elseif ($method == 'getAutoparts') {
			$data = array('mark' => $data['mark'], 'codaxp' => $data['codaxp'], 'grnum' => $data['grnum'], 'pdgrnum' => $data['pdgrnum']);
			$xparams = '&'.http_build_query($data);
		} else {
			$xparams = ($data) ? '&'.http_build_query($data) : '';
		}
		return Configure::read('AutoxpApi.url').$xparams;
	}
	
	private function getCacheKey($data, $method = '') {
		if ($method == 'getMarks') {
			$cache_key = 'index';
		} elseif ($method == 'getModelSections') {
			$data = array(
				'mark_id' => $data['mark'], 
				'model_id' => $data['model_id'], 
				'body_type' => $data['body_type'], 
				'fuel_id' => $data['fuel_id'], 
				'submodel' => $data['submodel']
			);
			$cache_key = http_build_query($data);
		} elseif ($method == 'getModelSubsections') {
			$data = array(
				'mark_id' => $data['mark'], 
				'model_id' => $data['model_id'], 
				'body_type' => $data['body_type'], 
				'fuel_id' => $data['fuel_id'], 
				'submodel' => $data['submodel'],
				'grnum' => $data['grnum']
			);
			$cache_key = http_build_query($data);
		} elseif ($method == 'getAutoparts') {
			$data = array(
				'mark_id' => $data['mark'], 
				'model_id' => $data['model_id'], 
				'body_type' => $data['body_type'], 
				'fuel_id' => $data['fuel_id'], 
				'submodel' => $data['submodel'],
				'grnum' => $data['grnum'],
				'pdgrnum' => $data['pdgrnum']
			);
			$cache_key = http_build_query($data);
		} else {
			$cache_key = http_build_query($data);
		}
		return $cache_key;
	}
	
	private function getProxyUsed() {
		return $this->proxy_used;
	}
	
	private function getCurlStatus() {
		return $this->curlStatus;
	}
	
	private function sendRequest($method, $data = array()) {
		$cache_key = $this->getCacheKey($data, $method);
		
		if (in_array($method, $this->staticMethods)) { 
			// для этих методов достаем сразу из кэша, т.к. данные не меняются
			$response = Cache::read($cache_key, 'autoxp');
			if ($response) {
				$this->writeDBLog($method, $data, 'CACHE', serialize($response));
				return $response;
			} elseif ($this->isBot()) {
				// если данных нету и это бот - возвращаем пустой ответ, чтобы не перегружать AutoXP запросами от ботов
				$this->writeDBLog($method, $data, 'CACHE', '');
				return array();
			}
		} else {
			// если ответы постоянно меняются в зав-ти от хэша
			if ($this->isBot()) {
				$response = Cache::read($cache_key, 'autoxp');
				return ($response) ? $response : array();
			}
		}
		
		$url = $this->getRequestURL($data, $method);
		$cookieFile = Configure::read('AutoxpApi.cookies');
		$curl = new Curl($url);
		
		// Определяем идет ли это запрос от поискового бота
		// если бот - перенаправляем на др.прокси-сервера для ботов - снимаем нагрузку с прокси для сайта
		// чтоб избежать блокировок со стороны сервиса
		$proxy_used = '';
		$curlStatus = '';
		if (!TEST_ENV) {
			$ip = $_SERVER['REMOTE_ADDR'];
			$proxy_type = ($this->isBot($ip)) ? 'Bot' : 'Site';
			$proxy = $this->loadModel('ProxyUse')->getProxy($proxy_type);
			$this->loadModel('ProxyUse')->useProxy($proxy['ProxyUse']['host']);
			$proxy_used = $proxy['ProxyUse']['host'];
			
			$curl->setOption(CURLOPT_PROXY, $proxy['ProxyUse']['host'])
				->setOption(CURLOPT_PROXYUSERPWD, $proxy['ProxyUse']['login'].':'.$proxy['ProxyUse']['password']);
		}
		// кэширование реализовано в обработке самих ответов, т.к. нет смысла хранить весь HTML страницы в кэше
		// проще хранить "чистый" ответ
		// и есть проблема с хэшами ссылок
		
		try {
			$this->writeLog('REQUEST', 'URL: '.$url.' DATA: '.json_encode($data));
			$response = $curl->setMethod(Curl::POST)
				// ->setOption(CURLOPT_FOLLOWLOCATION, true) // нужен для того, чтобы выставлялись куки
				->setOption(CURLOPT_COOKIEFILE, $cookieFile)
				->setOption(CURLOPT_COOKIEJAR, $cookieFile)
				->sendRequest();
			$this->writeLog('RESPONSE', $response);
			$curlStatus = $curl->getStatus();
			
			if ($response) {
				$html = str_get_html($response);
				if (($title = $html->find('title', 0)) && $title->plaintext == 'Object moved') {
					if (($a = $html->find('a', 0)) && isset($a->href) && $a->href) {
						if ($a->href == 'http://www.autoxp.ru/rus/error.aspx?code=602') {
							// AutoXP заблокировал запрос - превысили кол-во запросов
							throw new Exception('AutoxpApi: Too much requests');
						}
						
						// при запросе возникла ошибка - скорее всего куки просрочились
						
						// перенаправляем на указанный в ответе URL для того, чтобы выставлялись куки
						$_url = 'http://app.autoxp.ru/'.urldecode($a->href);
						$response = $curl->setMethod(Curl::POST)
							->setUrl($_url)
							->setOption(CURLOPT_COOKIEFILE, $cookieFile)
							->setOption(CURLOPT_COOKIEJAR, $cookieFile)
							->sendRequest();
							
						// повторяем запрос
						$response = $curl->setMethod(Curl::POST)
							->setUrl($url)
							->setOption(CURLOPT_COOKIEFILE, $cookieFile)
							->setOption(CURLOPT_COOKIEJAR, $cookieFile)
							->sendRequest();
						$curlStatus = $curl->getStatus();
					}
				}
			}
			
			if (!$response) {
				throw new Exception('AutoxpApi: No response from server');
			}
			
			$html = str_get_html($response);
			$method = '_'.$method;
			
			$_response = $this->$method($html);
			
			if (!$_response) {
				throw new Exception('AutoxpApi: Bad server response');
			}
		} catch (Exception $e) {
			if (!in_array($method, $this->staticMethods)) { // для этих методов мы уже искали в кэше
				// если ошибка - пытаемся все-таки достать ответ из кэша
				// иначе все-таки вызываем исключение
				$curlStatus['exception'] = $e->getMessage();
				$response = Cache::read($cache_key, 'autoxp');
				if ($response) {
					$this->writeDBLog($method, $data, 'CACHE', $response, $proxy_used, $curlStatus);
					return $response;
				}
			}
			
			$this->writeDBLog($method, $data, 'RESPONSE_ERROR', $response, $proxy_used, $curlStatus);
			throw $e;
			return '';
		}
		
		// все OK - запись в кэш
		Cache::write($cache_key, $_response, 'autoxp');
		$this->extractSearchID($html);
		$this->writeDBLog($method, $data, 'OK', serialize($_response), $proxy_used, $curlStatus);
		return $_response;
	}
	
	public function getMarks() {
		return $this->sendRequest('getMarks');
	}
	
	private function _getMarks($html) {
		$response = array();
		foreach($html->find('tr') as $tr) {
			$a = $tr->find('td', 1)->find('a', 0);
			if ($a && isset($a->href)) {
				$params = array();
				parse_str($a->href, $params);
				if (isset($params['mark'])) {
					$title = $a->plaintext;
					if ($title == 'CITROEN') {
						$title = 'CITROËN';
					} elseif ($title == 'MERCEDES BENZ') {
						$title = 'MERCEDES-BENZ';
					} elseif ($title == 'MERCEDES BENZ - гр.') {
						$title = 'MERCEDES TRUCKS';
					} elseif ($title == 'RENAULT - гр.') {
						$title = 'RENAULT TRUCKS';
					} elseif ($title == 'SSANG YONG') {
						$title = 'SSANGYONG';
					} elseif ($title == 'VOLKSWAGEN') {
						$title = 'VW';
					}
					$response[] = array('id' => $params['mark'], 'title' => $title);
				}
			}
		}
		return $response;
	}
	
	public function getModels($mark) {
		return $this->sendRequest('getModels', compact('mark'));
	}
	
	private function _getModels($html) {
		$response = array();
		foreach($html->find('select[name=serie] option') as $i => $e) {
			if (!$i) {
				// пропускаем первый пункт - выбор модели
				continue;
			}
			$response[] = array('id' => $e->value, 'title' => $e->plaintext);
		}
		
		if ($response) {
			$response = Hash::sort($response, '{n}.title', 'asc');
		}
		
		return $response;
	}
	
	public function getBodyTypes($mark, $serie) {
		return $this->sendRequest('getBodyTypes', compact('mark', 'serie'));
	}
	
	private function _getBodyTypes($html) {
		$response = array();
		foreach($html->find('select[name=kuzov] option') as $i => $e) {
			if (!$i) {
				// пропускаем первый пункт - выбор
				continue;
			}
			$response[] = array('id' => $e->value, 'title' => $e->plaintext);
		}
		if ($response) {
			$response = Hash::sort($response, '{n}.title', 'asc');
		}
		return $response;
	}

	public function getMotors($mark, $serie, $kuzov) {
		return $this->sendRequest('getMotors', compact('mark', 'serie', 'kuzov'));
	}
	
	private function _getMotors($html) {
		$response = array();
		foreach($html->find('select[name=fuel] option') as $i => $e) {
			if (!$i) {
				// пропускаем первый пункт - выбор
				continue;
			}
			$response[] = array('id' => $e->value, 'title' => $e->plaintext);
		}
		
		if ($response) {
			$response = Hash::sort($response, '{n}.title', 'asc');
		}
		return $response;
	}
	
	public function getModelsInfo($mark, $serie, $kuzov, $fuel) {
		return $this->sendRequest('getModelsInfo', compact('mark', 'serie', 'kuzov', 'fuel'));
	}
	
	private function _getModelsInfo($html) {
		$response = array();
		if ($table = $html->find('table', 3)) {
			foreach($table->find('tr') as $i => $tr) {
				if (!($i % 2)) {
					continue;
				}
				$motor = explode('/', trim($tr->find('td', 1)->plaintext));
				$aYear = array();
				$j = 3;
				while ($td = $tr->find('td', $j)) {
					if (($year = trim($td->plaintext)) && $year != '&nbsp') {
						$aYear[] = $year;
					}
					$j++;
				}
				$date_from = '';
				$date_to = '';
				if ($aYear) {
					$date_from = $aYear[0];
					$date_to = array_pop($aYear);
				}
				$a = $tr->find('td', 0)->find('a', 0);
				list($spam, $hash) = explode("','", $a);
				
				$response[] = array(
					'hash' => $hash,
					'model' => $a->plaintext,
					'volume' => trim($motor[0]),
					'kw' => trim($motor[1]),
					'hp' => trim($motor[2]),
					'date_issue' => $date_from.' - '.$date_to,
					'kpp' => trim($tr->find('td', 2)->plaintext)
				);
			}
		}
		if ($response) {
			$response = Hash::sort($response, '{n}.model', 'asc');
		}
		return $response;
	}
	
	public function getModelSections($mark, $model_id, $body_type, $fuel_id, $codaxp, $submodel) {
		return $this->sendRequest('getModelSections', compact('mark', 'model_id', 'body_type', 'fuel_id', 'submodel', 'codaxp'));
	}
	
	private function _getModelSections($html) {
		$table = $html->find('table', 2);
		$response = array();
		foreach($table->find('tr') as $i => $tr) {
			if ($i < 3) {
				continue;
			}
			$a = $tr->find('td', 1)->find('a', 0);
			parse_str($a->href, $u);
			$response[] = array(
				// 'hash' => $codaxp,
				'id' => $u['grnum'],
				'title' => $a->plaintext
			);
		}
		return $response;
	}
	
	public function getModelSubsections($mark, $model_id, $body_type, $fuel_id, $codaxp, $submodel, $grnum) {
		$response = $this->sendRequest('getModelSubsections', compact('mark', 'model_id', 'body_type', 'fuel_id', 'submodel', 'grnum', 'codaxp'));
		if ($response) {
			foreach($response as &$row) {
				$row['grnum'] = $grnum;
			}
		}
		return $response;
	}
	
	private function _getModelSubsections($html) {
		$response = array();
		$table = $html->find('table', 3);
		if ($table) {
			foreach($table->find('a') as $i => $a) {
				parse_str($a->href, $u);
				$img = $a->find('img', 0);
				$thumb = 'http://app.autoxp.ru/pscomplex/'.$img->src;
				$response[] = array(
					// 'hash' => $codaxp,
					// 'grnum' => $grnum,
					'pdgrnum' => $u['pdgrnum'],
					'title' => str_replace('...', '', $img->alt),
					'thumb' => $thumb,
					'img' => str_replace('m.jpg', 'g.gif', $thumb)
				);
			}
		}
		return $response;
	}
	
	public function getAutoparts($mark, $model_id, $body_type, $fuel_id, $codaxp, $submodel, $grnum, $pdgrnum) {
		return $this->sendRequest('getAutoparts', compact('mark', 'model_id', 'body_type', 'fuel_id', 'codaxp', 'submodel', 'grnum', 'pdgrnum'));
	}
	
	private function _getAutoparts($html) {
		// почему-то баг с map + area
		$_html = $html->save();
		$pos = strpos($_html, '<map');
		$pos2 = strpos($_html, '</map');
		$image_map = substr($_html, $pos, $pos2 - $pos + 6);
		
		$table = $html->find('table.TABLE2', 0);
		$response = array('items' => array());
		if ($table) {
			foreach($table->find('tr') as $i => $tr) {
				if (!$i) {
					continue;
				}

				$npp = $tr->find('td', 0);
				if ($npp->find('b', 0)) {
					$npp = $npp->find('b', 0)->plaintext;
				} else {
					$npp = $npp->plaintext;
				}
				$response['items'][] = array(
					'tr_id' => $tr->id,
					'npp' => $npp,
					'title' => $tr->find('td', 1)->plaintext,
					'detail_num' => $tr->find('td', 2)->find('a', 0)->plaintext
				);
			}
		}
		
		if ($response['items']) {
			$response['image_map'] = $image_map;
			return $response;
		}
		return array();
	}
	
	protected function extractSearchID($html) {
		$e = $html->find('p[align=right] font[color=#777777]', 0);
		$this->searchID = ($e) ? str_replace(array('[', ']'), '', $e->plaintext) : '';
	}
	
	public function getSearchID() {
		return $this->searchID;
	}
	
	private function sendSearchRequest($ses, $vin) {
		$data = compact('vin', 'ses');
		$url = Configure::read('AutoxpApi.search_url').'&'.http_build_query($data);
		$cookieFile = Configure::read('AutoxpApi.cookies');
		$curl = new Curl($url);
		
		if (!TEST_ENV) {
			// Определяем идет ли это запрос от поискового бота
			// если бот - перенаправляем на др.прокси-сервера для ботов - снимаем нагрузку с прокси для сайта
			// чтоб избежать блокировок со стороны сервиса
			$proxy_type = ($this->isBot()) ? 'Bot' : 'Site';
			$proxy = $this->loadModel('ProxyUse')->getProxy($proxy_type);
			$this->loadModel('ProxyUse')->useProxy($proxy['ProxyUse']['host']);
			
			$curl->setOption(CURLOPT_PROXY, $proxy['ProxyUse']['host'])
				->setOption(CURLOPT_PROXYUSERPWD, $proxy['ProxyUse']['login'].':'.$proxy['ProxyUse']['password']);
		}
		
		$this->writeLog('REQUEST', 'URL: '.$url.' DATA: '.json_encode($data));
		$response = $curl->setMethod(Curl::GET)
			->setOption(CURLOPT_COOKIEFILE, $cookieFile)
			->setOption(CURLOPT_COOKIEJAR, $cookieFile)
			->sendRequest();
		$this->writeLog('RESPONSE', $response);
		
		if (!$response) {
			throw new Exception('AutoxpApi: No response from server');
		}
		
		return $response;
	}
	
	public function searchVIN($searchID, $vin) {
		if ($this->isBot()) {
			return array();
		}
		$response = $this->sendSearchRequest($searchID, $vin);
		$response = str_replace('<br>', '</a><br>', $response);
		$html = str_get_html($response);
		$response = array();
		foreach($html->find('a') as $a) {
			parse_str($a->href, $u);
			$title = str_replace(array('[', ']'), '|', $a->plaintext);
			list($title, $year_issue) = explode('|', $title);
			$response[] = array(
				'hash' => $u['codaxp'],
				'mark' => $u['mark'],
				'title' => $title,
				'year_issue' => $year_issue
			);
		}
		
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		return $response;
	}
	
	public function searchSections($mark, $codaxp) {
		if ($this->isBot()) {
			return array();
		}
		
		$request = compact('mark', 'codaxp');
		// $specURL = Configure::read('AutoxpApi.url').'&mark='.$mark.'&codaxp='.$codaxp; // костыль для некорректных хэшей
		$response = $this->sendRequest($request, $specURL);
		
		$html = str_get_html($response);
		$response = array();
		$a = $html->find('a[title=Выбор модели]', 0);
		if ($a) {
			parse_str($a->href, $u);
			$response = array(
				'mark' => $u['mark'], 
				'model' => $u['serie'], 
				'body_type' => $u['kuzov'], 
				'fuel' => $u['fuel'], 
				'hash' => $codaxp
			);
		}
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		return $response;
	}
}
