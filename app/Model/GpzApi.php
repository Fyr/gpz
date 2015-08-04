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
		
		return array_merge($tdData, $zzapData);
	}
	
	public function getPrices($brand, $partnumber, $lFullInfo) {
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
		
		return $this->processPrices(array_merge($zzapData, $tdData), $lFullInfo);
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
	
	private function processPrices($table, $lFullInfo) {
		$table = Hash::sort($table, '{n}.price2', 'asc');
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
