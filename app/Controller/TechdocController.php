<?php
App::uses('AppController', 'Controller');
App::uses('TechDocApi', 'Model');
class TechdocController extends AppController {
	public $name = 'Techdoc';
	public $uses = array('TechDocApi');
	public $helpers = array('ObjectType');
	
	protected $Subsection;
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->currMenu = 'Products';
	}
	
	public function index($brand = '') {
		$brands = $this->TechDocApi->getMarks();
		$this->set('aCatalog', $brands);
		
		if ($brand) {
			$brand = strtoupper($brand);
			$brands = Hash::combine($brands, '{n}.title', '{n}.id');
			if (isset($brands[$brand]) && $brands[$brand]) {
				$this->redirect(array('action' => 'brand', $brands[$brand]));
			}
		}
	}
	
	public function brand($mark_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = $this->TechDocApi->getModels($mark_id);
		$this->set('aModels', $aModels);
		unset($aModels);
		
		$title = $mark['title'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function model($mark_id, $model_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aSubModels = $this->TechDocApi->getModelSections($mark_id, $model_id);
		$this->set('aSubModels', $aSubModels);
		unset($aSubModels);
		
		$title = $mark['title'].' '.$model['title'].' '.$model['date_issue'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
	
	public function subsections($mark_id, $model_id, $type_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aSubModels = Hash::combine($this->TechDocApi->getModelSections($mark_id, $model_id), '{n}.id', '{n}');
		$submodel = $aSubModels[$type_id];
		$this->set('submodel', $aSubModels[$type_id]);
		unset($aSubModels);
		
		$aSubsections = $this->TechDocApi->getModelSubsections($mark_id, $model_id, $type_id);
		$this->set('aSubsections', $aSubsections);

		$title = $mark['title'].' '.$model['title'].' '.$submodel['type'].' '.$model['date_issue'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => "Каталог запчастей для {$title}, запчасти для {$title}",
			'descr' => "На нашем сайте вы можете приобрести лучшие запчасти {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
		
		// Сохраняем осн.узлы для того, чтобы навесить картинки
		$this->loadModel('Subsection');
		$this->Subsection->saveMainSubsections($aSubsections);
		
		// получаем список узлов с картинками
		$ids = Hash::extract($aSubsections, '{n}.id');
		$conditions = array('Subsection.td_id' => $ids, 'Media.main' => 1);
		$order = 'Subsection.td_id';
		$subsections = $this->Subsection->find('all', compact('conditions', 'order'));
		$this->set('subsections', $subsections);
	}
	
	public function autoparts($mark_id, $model_id, $type_id, $node_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$mark = $aCatalog[$mark_id];
		$this->set('mark', $mark);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$model = $aModels[$model_id];
		$this->set('model', $model);
		unset($aModels);
		
		$aSubModels = Hash::combine($this->TechDocApi->getModelSections($mark_id, $model_id), '{n}.id', '{n}');
		$submodel = $aSubModels[$type_id];
		$this->set('submodel', $aSubModels[$type_id]);
		unset($aSubModels);
		
		$aSubsections = Hash::combine($this->TechDocApi->getModelSubsections($mark_id, $model_id, $type_id), '{n}.id', '{n}');
		$subsection = $aSubsections[$node_id];
		$this->set('subsection', $subsection);
		unset($aSubsections);
		
		$aAutoparts = $this->TechDocApi->getAutoparts($mark_id, $model_id, $type_id, $node_id);
		$this->set('aAutoparts', $aAutoparts);
		unset($aAutoparts);
		
		$title = $subsection['name'].' для '.$mark['title'].' '.$model['title'].' '.$submodel['type'].' '.$model['date_issue'];
		$this->seo = array(
			'title' => $title, 
			'keywords' => $subsection['name']." - каталог запчастей ".$mark['title'].' '.$model['title'].' '.$submodel['type'].' '.$model['date_issue'].", запчасти ".$mark['title'].' '.$model['title'].' '.$submodel['type'].' '.$model['date_issue'],
			'descr' => "На нашем сайте вы можете приобрести {$title} в Белорусии. Низкие цены на запчасти, быстрая доставка по стране, диагностика, ремонт."
		);
	}
}
