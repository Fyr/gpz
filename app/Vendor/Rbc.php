<?
class Rbc {
	const url = 'http://cbrates.rbc.ru/tsv/';
	const file = '.tsv';
	private $date = 0;

	public function __construct($date = null) {
		if ($date == null) {
			$date = time();
		}
		$this->date = $date;
	}

	public function curs($currency_code) {
		$url = self::url;
		$curs = 0;
		// try {
			if (!is_numeric($currency_code)) {
				throw new Exception('Incorrect currency code');
			}
			$url .= $currency_code . '/';
			if ($this->date <= 0) {
				throw new Exception('Incorrect currency date');
			}
			$url .= date('Y/m/d', $this->date);
			$url .= self::file;

			$page = file_get_contents($url);
			$curs = $this->parse($page);
		/*
		} catch (Exception $e) {
			echo 'Не удалось получить курс валюты. ', $e->getMessage();
		}
		*/
		return $curs;
	}

	private function parse($file) {
		if (empty($file)) {
			throw new Exception('Bad server answer or server is not available');
		}
		$curs = explode("\t", $file);
		if (!empty($curs[1])) {
			return $curs[1];
		} else {
			throw new Exception('No server response on specified date');
		}
	}
}