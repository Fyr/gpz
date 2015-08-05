<?php
App::uses('AppController', 'Controller');
class TechdocController extends AppController {
	public $name = 'Techdoc';
	public $uses = array('TechDocApi');
	public $helpers = array('ObjectType');
	
	public function index() {
		$this->set('aCatalog', $this->TechDocApi->getMarks());
	}
	
	public function brand($mark_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		unset($aCatalog);
		
		$aModels = $this->TechDocApi->getModels($mark_id);
		$this->set('aModels', $aModels);
		unset($aModels);
	}
	
	public function model($mark_id, $model_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$this->set('model', $aModels[$model_id]);
		unset($aModels);
		
		$aSubModels = $this->TechDocApi->getModelSections($mark_id, $model_id);
		$this->set('aSubModels', $aSubModels);
		unset($aSubModels);
	}
	
	public function subsections($mark_id, $model_id, $type_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$this->set('model', $aModels[$model_id]);
		unset($aModels);
		
		$aSubModels = Hash::combine($this->TechDocApi->getModelSections($mark_id, $model_id), '{n}.id', '{n}');
		$this->set('submodel', $aSubModels[$type_id]);
		unset($aSubModels);
		
		$aSubsections = $this->TechDocApi->getModelSubsections($mark_id, $model_id, $type_id);
		$this->set('aSubsections', $aSubsections);
		unset($aSubsections);
	}
	
	public function autoparts($mark_id, $model_id, $type_id, $node_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		unset($aCatalog);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$this->set('model', $aModels[$model_id]);
		unset($aModels);
		
		$aSubModels = Hash::combine($this->TechDocApi->getModelSections($mark_id, $model_id), '{n}.id', '{n}');
		$this->set('submodel', $aSubModels[$type_id]);
		unset($aSubModels);
		
		$aSubsections = Hash::combine($this->TechDocApi->getModelSubsections($mark_id, $model_id, $type_id), '{n}.id', '{n}');
		$this->set('subsection', $aSubsections[$node_id]);
		unset($aSubsections);
		
		$aAutoparts = $this->TechDocApi->getAutoparts($mark_id, $model_id, $type_id, $node_id);
		$this->set('aAutoparts', $aAutoparts);
		unset($aAutoparts);
	}
}
