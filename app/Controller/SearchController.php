<?php
App::uses('AppController', 'Controller');
App::uses('SiteRouter', 'Vendor');
class SearchController extends AppController {
	public $components = array('Auth');
	public $name = 'Search';
	public $uses = array('GpzApi', 'GpzOffer');
	
	protected $CarSubtype, $CarSubsection;
	
	public function beforeFilter() {
		$this->Auth->allow(array('index', 'price'));
		parent::beforeFilter();
		$this->currMenu = 'Products';
	}
	
	public function index() {
		$q = '';
		if (($carSubtype = $this->request->param('carSubtype')) && ($carSubsection = $this->request->param('slug'))) {
			$this->loadModel('CarSubtype');
			$this->loadModel('CarSubsection');
			
			$carSubtype = $this->CarSubtype->findBySlug($carSubtype);
			$carSubsection = $this->CarSubsection->findBySlug($carSubsection);
			if ($carSubtype && $carSubsection) {
				$q = $carSubtype['CarSubtype']['title'].' '.$carSubsection['CarSubsection']['title'];
				
				$this->seo = $carSubsection['Seo'];
				$this->set('article', Hash::merge($carSubsection, $carSubtype));
			}
		} else {
			$q = $this->request->query('q');
		}

		if (!$q){
			return $this->setError('Введите текст в строку поиска');
		} 
		
		try {
			$this->setResult($this->GpzApi->search($q));
			
			if (!(isset($this->seo['title']) && $this->seo['title'])) {
				$this->seo = array(
					'title' => $q, 
					'keywords' => "Каталог запчастей, запчасти, {$q}",
					'descr' => "На нашем сайте вы можете приобрести {$q} - лучшие запчасти в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
				);
			}
		}  catch (Exception $e){
			// $this->setError($e->getMessage());
			$this->redirect($_SERVER['REQUEST_URI']);
		}		
	}
	
	public function price(){
		$lFullInfo = $this->Auth->loggedIn();
		
		$number = $this->request->query('number');
		$brand = $this->request->query('brand');
		
		if (!($number && $brand)) {
			return $this->setError('Неверный запрос');
		}
		
		$aSorting = array(
			'brand' => 'Производитель',
			'partnumber' => 'Номер',
			'title' => 'Наименование',
			'qty' => 'Наличие',
			'price2' => 'Цена'
		);
		$this->set('aSorting', $aSorting);
		$aOrdering = array(
			'asc' => 'по возрастанию',
			'desc' => 'по убыванию'
		);
		$this->set('aOrdering', $aOrdering);
		
		$sort = $this->request->query('sort');
		if (!$sort || !in_array($sort, array_keys($aSorting))) {
			$sort = 'price2';
		}
		$order = $this->request->query('order');
		if (!$order || !in_array($order, array_keys($aOrdering))) {
			$order = 'asc';
		}
		$this->set('sort', $sort);
		$this->set('order', $order);
		
		try {	
			$content = $this->GpzApi->getPrices($brand, $number, $sort, $order, $lFullInfo);
			$this->setResult($content);
			$this->set('lFullInfo', $lFullInfo);
			$this->set('aOfferTypeOptions', GpzOffer::options());
			
			$title = $number.' '.$brand;
			foreach($content as $row) {
				if ($row['title'] != '(БЕЗ НАЗВАНИЯ)' && $row['title']) {
					$title = $row['title'];
					break;
				}
			}
			$this->seo = array(
				'title' => 'Цены на '.$title, 
				'keywords' => "Цены на {$title}, каталог запчастей, запчасти",
				'descr' => "На нашем сайте вы можете приобрести {$title} - лучшие запчасти в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
			);
		}  catch (Exception $e){
			// $this->setError($e->getMessage());
			$this->redirect($_SERVER['REQUEST_URI']);
		}
	}
	
	private function setResult($result){
		$this->set('content', $result);
	}
	
	private function setError($errorText = 'Произошла ошибка'){
		$this->set('errorText', $errorText);
	}
	
}
