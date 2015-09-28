<?php
App::uses('AppModel', 'Model');
App::uses('ProxyUse', 'Model');
App::uses('Curl', 'Vendor');
App::import('Vendor', 'simple_html_dom');

class AutoxpApi extends AppModel {
	public $useTable = false;
	
	protected $searchID;
	
	private function writeLog($actionType, $data = '') {
		$string = date('d-m-Y H:i:s').' '.$actionType.' '.$data;
		file_put_contents(Configure::read('AutoxpApi.log'), $string."\r\n", FILE_APPEND);
	}
	
	private function sendRequest($data = array()) {
		$xparams = ($data) ? '&'.http_build_query($data) : '';
		$url = Configure::read('AutoxpApi.url').$xparams;
		$cookieFile = Configure::read('AutoxpApi.cookies');
		$curl = new Curl($url);
		
		// Определяем идет ли это запрос от поискового бота
		// если бот - перенаправляем на др.прокси-сервера для ботов - снимаем нагрузку с прокси для сайта
		// чтоб избежать блокировок со стороны сервиса
		$ip = $_SERVER['REMOTE_ADDR'];
		$proxy_type = ($this->isBot($ip)) ? 'Bot' : 'Site';
		$proxy = $this->loadModel('ProxyUse')->getProxy($proxy_type);
		$this->loadModel('ProxyUse')->useProxy($proxy['ProxyUse']['host']);
		
		$curl->setOption(CURLOPT_PROXY, $proxy['ProxyUse']['host'])
			->setOption(CURLOPT_PROXYUSERPWD, $proxy['ProxyUse']['login'].':'.$proxy['ProxyUse']['password']);
		
		// кэширование реализовано в обработке самих ответов, т.к. нет смысла хранить весь HTML страницы в кэше
		// проще хранить "чистый" ответ
		// и есть проблема с хэшами ссылок
		$this->writeLog('REQUEST', 'URL: '.$url.' DATA: '.json_encode($data));
		$response = $curl->setMethod(Curl::POST)
			// ->setOption(CURLOPT_FOLLOWLOCATION, true) // нужен для того, чтобы выставлялись куки
			->setOption(CURLOPT_COOKIEFILE, $cookieFile)
			->setOption(CURLOPT_COOKIEJAR, $cookieFile)
			->sendRequest();
		$this->writeLog('RESPONSE', $response);
		
		$html = str_get_html($response);
		if (($title = $html->find('title', 0)) && $title->plaintext == 'Object moved') {
			if (($a = $html->find('a', 0)) && isset($a->href) && $a->href) {
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
			}
			
		}
		
		if (!$response) {
			throw new Exception('AutoxpApi: No response from server');
		}
		
		return $response;
	}
	
	private function sendSearchRequest($ses, $vin) {
		$data = compact('vin', 'ses');
		$url = Configure::read('AutoxpApi.search_url').'&'.http_build_query($data);
		$cookieFile = Configure::read('AutoxpApi.cookies');
		$curl = new Curl($url);
		
		// Определяем идет ли это запрос от поискового бота
		// если бот - перенаправляем на др.прокси-сервера для ботов - снимаем нагрузку с прокси для сайта
		// чтоб избежать блокировок со стороны сервиса
		$ip = $_SERVER['REMOTE_ADDR'];
		$proxy_type = ($this->isBot($ip)) ? 'Bot' : 'Site';
		$proxy = $this->loadModel('ProxyUse')->getProxy($proxy_type);
		$this->loadModel('ProxyUse')->useProxy($proxy['ProxyUse']['host']);
		
		$curl->setOption(CURLOPT_PROXY, $proxy['ProxyUse']['host'])
			->setOption(CURLOPT_PROXYUSERPWD, $proxy['ProxyUse']['login'].':'.$proxy['ProxyUse']['password']);
			
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
	
	public function getMarks() {
		$cache_key = 'index';
		$response = Cache::read($cache_key, 'autoxp');
		if ($response) {
			return $response;
		}
		$response = $this->sendRequest('');
		$html = str_get_html($response);
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
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		Cache::write($cache_key, $response, 'autoxp');
		// $this->extractSearchID($html);
		return $response;
	}
	
	public function getModels($mark) {
		$request = compact('mark');
		$cache_key = http_build_query($request);
		$response = Cache::read($cache_key, 'autoxp');
		if ($response) {
			return $response;
		}
		
		$response = $this->sendRequest($request);
		
		$html = str_get_html($response);
		$response = array();
		foreach($html->find('select[name=serie] option') as $i => $e) {
			if (!$i) {
				// пропускаем первый пункт - выбор модели
				continue;
			}
			$response[] = array('id' => $e->value, 'title' => $e->plaintext);
		}
		
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		
		$response = Hash::sort($response, '{n}.title', 'asc');
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}
	
	public function getBodyTypes($mark, $serie) {
		$request = compact('mark', 'serie');
		$cache_key = http_build_query($request);
		$response = Cache::read($cache_key, 'autoxp');
		if ($response) {
			return $response;
		}
		
		$response = $this->sendRequest($request);
		$html = str_get_html($response);
		$response = array();
		foreach($html->find('select[name=kuzov] option') as $i => $e) {
			if (!$i) {
				// пропускаем первый пункт - выбор
				continue;
			}
			$response[] = array('id' => $e->value, 'title' => $e->plaintext);
		}
		
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		
		$response = Hash::sort($response, '{n}.title', 'asc');
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}

	public function getMotors($mark, $serie, $kuzov) {
		$request = compact('mark', 'serie', 'kuzov');
		$cache_key = http_build_query($request);
		$response = Cache::read($cache_key, 'autoxp');
		if ($response) {
			return $response;
		}
		
		$response = $this->sendRequest($request);
		$html = str_get_html($response);
		$response = array();
		foreach($html->find('select[name=fuel] option') as $i => $e) {
			if (!$i) {
				// пропускаем первый пункт - выбор
				continue;
			}
			$response[] = array('id' => $e->value, 'title' => $e->plaintext);
		}
		
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		
		$response = Hash::sort($response, '{n}.title', 'asc');
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}
	
	public function getModelsInfo($mark, $serie, $kuzov, $fuel) {
		$request = compact('mark', 'serie', 'kuzov', 'fuel');
		$cache_key = http_build_query($request);
		try {
			$response = $this->sendRequest($request);
		} catch (Exception $e) {
			$response = Cache::read($cache_key, 'autoxp');
			if ($response) {
				return $response;
			} else {
				throw $e;
			}
		}
		
		$html = str_get_html($response);
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
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		$response = Hash::sort($response, '{n}.model', 'asc');
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}
	
	public function getModelSections($mark, $codaxp, $cache_params) {
		$request = compact('mark', 'codaxp');
		$cache_key = http_build_query($cache_params);
		try {
			$response = $this->sendRequest($request);
		} catch (Exception $e) {
			$response = Cache::read($cache_key, 'autoxp');
			if ($response) {
				return $response;
			} else {
				throw $e;
			}
		}
		$html = str_get_html($response);
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
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}
	
	public function getModelSubsections($mark, $codaxp, $grnum, $cache_params) {
		$request = compact('mark', 'codaxp', 'grnum');
		$cache_key = http_build_query($cache_params);
		try {
			$response = $this->sendRequest($request);
		} catch (Exception $e) {
			$response = Cache::read($cache_key, 'autoxp');
			if ($response) {
				return $response;
			} else {
				throw $e;
			}
		}
		
		$html = str_get_html($response);
		$table = $html->find('table', 3);
		$response = array();
		if ($table) {
			foreach($table->find('a') as $i => $a) {
				parse_str($a->href, $u);
				$img = $a->find('img', 0);
				$thumb = 'http://app.autoxp.ru/pscomplex/'.$img->src;
				$response[] = array(
					// 'hash' => $codaxp,
					'grnum' => $grnum,
					'pdgrnum' => $u['pdgrnum'],
					'title' => str_replace('...', '', $img->alt),
					'thumb' => $thumb,
					'img' => str_replace('m.jpg', 'g.gif', $thumb)
				);
			}
		}
		
		if (!$response) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}
	
	public function getAutoparts($mark, $codaxp, $grnum, $pdgrnum, $cache_params) {
		$request = compact('mark', 'codaxp', 'grnum', 'pdgrnum');
		$cache_key = http_build_query($cache_params);
		try {
			$response = $this->sendRequest($request);
		} catch (Exception $e) {
			$response = Cache::read($cache_key, 'autoxp');
			if ($response) {
				return $response;
			} else {
				throw $e;
			}
		}
		// почему-то баг с map + area
		$pos = strpos($response, '<map');
		$pos2 = strpos($response, '</map');
		$html = substr($response, $pos, $pos2 - $pos + 6);
		$image_map = $html;
		
		$html = str_get_html($response);
		$table = $html->find('table.TABLE2', 0);
		$response = array('items' => array());
		if ($table) {
			foreach($table->find('tr') as $i => $tr) {
				if (!$i) {
					continue;
				}
				
				$response['items'][] = array(
					'tr_id' => $tr->id,
					'npp' => $tr->find('td', 0)->find('b', 0)->plaintext,
					'title' => $tr->find('td', 1)->plaintext,
					'detail_num' => $tr->find('td', 2)->find('a', 0)->plaintext
				);
			}
		}
		if (!$response['items']) {
			throw new Exception('AutoxpApi: Bad server response');
		}
		$response['image_map'] = $image_map;
		
		Cache::write($cache_key, $response, 'autoxp');
		$this->extractSearchID($html);
		return $response;
	}
	
	protected function extractSearchID($html) {
		$this->searchID = str_replace(array('[', ']'), '', $html->find('p[align=right] font[color=#777777]', 0)->plaintext);
	}
	
	public function getSearchID() {
		return $this->searchID;
	}
	
	public function searchVIN($searchID, $vin) {
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
		$request = compact('mark', 'codaxp');
		$response = $this->sendRequest($request);
		
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
