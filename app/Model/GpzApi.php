<?php
App::uses('AppModel', 'Model');
App::uses('ZzapApi', 'Model');
App::uses('TechDocApi', 'Model');
class GpzApi extends AppModel {
	
	public function search($q) {
		$this->ZzapApi = $this->loadModel('ZzapApi');
		$this->TechDocApi = $this->loadModel('TechDocApi');
		
		$e = null;
		$tdData = array();
		try {
			$tdData = $this->TechDocApi->getSuggests($q);
		} catch (Exception $e) {
			
		}
		
		$zzapData = array();
		try {
			$zzapData = $this->ZzapApi->getSuggests($q);
		} catch (Exception $e) {
		}
		
		if (!$zzapData && !$tdData) {
			if ($e) {
				throw $e;
			}
		}
		
		return $this->processSuggests(array_merge($tdData, $zzapData));
	}
	
	/**
	 * Обьединяем детали по одинаковому номеру и производителю
	 */
	private function processSuggests($data) {
		// Шаг 1. Находим все лексемы и прибиваем все к нижнему регистру
		$aData = array();
		$aWords = array();
		
		foreach($data as &$row) {
			// Приводим номера к одному виду - удаляем пробелы, дефисы, подчеркивания, нули
			$row['partnumber'] = str_replace(array(' ', '-', '_'), '', $row['partnumber']);
			
			$row['_title'] = mb_strtolower($row['title']);
			
			// вычищаем строку от псевдосимволов
			$row['_title'] = str_replace(array('.', '(', ')', '-', '=', ';'), ' ', $row['_title']);
			while (strpos($row['_title'], '  ') !== false) {
				$row['_title'] = str_replace('  ', ' ', $row['_title']);
			}
			
			$row['_words'] = explode(' ', trim($row['_title']));
			$aWords = array_unique(array_merge($aWords, $row['_words']));
			$aData[$row['brand'].$row['partnumber']][] = $row;
		}
		unset($row); // оч странно но почему то без этого меняется значение последнего эл-та в $data
		
		// Шаг 2. Вычищаем односимвольные лексемы и вычисляем их вес (кол-во повторений)
		$aWeight =  array();
		$totalWeight = 0;
		foreach($aWords as $i => $word) {
			if (mb_strlen($word) <= 1) {
				unset($aWords[$i]);
			}
		}
		foreach($aWords as $i => $word) {
			if (!isset($aWeight[$word])) {
				$aWeight[$word] = 0;
			}
			foreach($data as $row) {
				if (in_array($word, $row['_words'])) {
					$aWeight[$word]++;
					$totalWeight++;
				}
			}
		}
		
		// Шаг 3. Вычисляем вес строки по каждой лексеме от общего веса и сортируем по весу
		foreach($aData as $brandnum => &$rows) {
			foreach($rows as &$row) {
				$factor = 0;
				foreach($aWords as $word) {
					if (in_array($word, $row['_words'])) { // ищем по полному совпадению лексемы
						$factor+= $aWeight[$word];
						// $row['__factor'][] = $word.' '.$aWeight[$word];
					}
				}
				$row['_factor'] = $factor / $totalWeight;
			}
			$rows = Hash::sort($rows, '{n}._factor', 'desc');
		}
		
		// Шаг 4. Извлекаем наиболее весомые строки
		$data = array();
		foreach($aData as $brandnum => $rows) {
			unset($rows[0]['_words']);
			unset($rows[0]['_title']);
			unset($rows[0]['_factor']);
			$data[] = $rows[0];
		}
		return $data;
	}
	
	public function getPrices($brand, $partnumber, $sort, $order, $lFullInfo) {
		$this->ZzapApi = $this->loadModel('ZzapApi');
		$this->TechDocApi = $this->loadModel('TechDocApi');
		
		$tdData = array();
		try {
			$brandId = $this->getTechDocBrandId($brand, $partnumber);
			if ($brandId) {
				$tdData = $this->TechDocApi->getPrices($partnumber, $brandId);
			}
		} catch (Exception $e) {
		}
		
		$zzapData = array();
		try {
			$zzapData = $this->ZzapApi->getItemInfo($brand, $partnumber);
		} catch (Exception $e) {
		}
		
		if (!$zzapData && !$tdData) {
			throw $e;
		}
		
		return $this->processPrices(array_merge($zzapData, $tdData), $sort, $order, $lFullInfo);
	}
	
	private function processPricesByOfferType($table, $lFullInfo) {
		$table = Hash::sort($table, '{n}.offer_type', 'asc');
		$_table = array();
		foreach($table as $item) {
			$_table[$item['offer_type']][] = $item;
		}
		foreach($_table as $offer_type => &$rows) {
			$rows = Hash::sort($rows, '{n}.brand', 'asc');
			$_rows = array();
			
			foreach($rows as $item) {
				$_rows[$item['brand']][] = $item;
			}
			foreach($_rows as $brand => &$items) {
				$items = Hash::sort($items, '{n}.price', 'asc');
				if (!$lFullInfo) {
					$items = array($items[0]);
				}
			}
			if (!$lFullInfo) {
				$_rows = Hash::sort($_rows, '{s}.{n}.price', 'asc');
			}
			$rows = $_rows;
		}
		return $_table;
	}
	
	private function processPrices($table, $sort, $order, $lFullInfo) {
		$table = Hash::sort($table, '{n}.'.$sort, $order);
		if ($sort == 'price2') {
			return $table;
		}
		
		// вторичная сортировка - по цене
		$_table = array();
		foreach($table as $row) {
			$_table[$row[$sort]][] = $row;
		}
		foreach($_table as $k => $rows) {
			$_table[$k] = Hash::sort($rows, '{n}.price2', 'asc');
		}
		$table = array();
		foreach($_table as $rows) {
			foreach($rows as $row) {
				$table[] = $row;
			}
		}
		return $table;
	}
	
	private function getTechDocBrandId($brand, $partnumber) {
		$this->TechDocApi = $this->loadModel('TechDocApi');
		$articles = $this->TechDocApi->getSuggests($partnumber);
		if ($articles) {
			foreach($articles as $row) {
				if ($brand === $row['brand']) {
					return $row['provider_data']['brand_id'];
				}
			}
		}
		return false;
	}
}
