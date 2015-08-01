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
		$aModels = $this->TechDocApi->getModels($mark_id);
		$this->set('aModels', $aModels);
	}
	
	public function model($mark_id, $model_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$this->set('model', $aModels[$model_id]);
		
		$aSubModels = $this->TechDocApi->getModelSections($mark_id, $model_id);
		$this->set('aSubModels', $aSubModels);
	}
	
	public function subsections($mark_id, $model_id, $type_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$this->set('model', $aModels[$model_id]);
		
		$aSubModels = Hash::combine($this->TechDocApi->getModelSections($mark_id, $model_id), '{n}.id', '{n}');
		$this->set('submodel', $aSubModels[$type_id]);
		
		$aSubsections = $this->TechDocApi->getModelSubsections($mark_id, $model_id, $type_id);
		$this->set('aSubsections', $aSubsections);
	}
	
	public function autoparts($mark_id, $model_id, $type_id, $node_id) {
		$aCatalog = Hash::combine($this->TechDocApi->getMarks(), '{n}.id', '{n}');
		$this->set('mark', $aCatalog[$mark_id]);
		
		$aModels = Hash::combine($this->TechDocApi->getModels($mark_id), '{n}.id', '{n}');
		$this->set('model', $aModels[$model_id]);
		
		$aSubModels = Hash::combine($this->TechDocApi->getModelSections($mark_id, $model_id), '{n}.id', '{n}');
		$this->set('submodel', $aSubModels[$type_id]);
		
		$aSubsections = Hash::combine($this->TechDocApi->getModelSubsections($mark_id, $model_id, $type_id), '{n}.id', '{n}');
		$this->set('subsection', $aSubsections[$node_id]);
		
		$aAutoparts = $this->TechDocApi->getAutoparts($mark_id, $model_id, $type_id, $node_id);
		$this->set('aAutoparts', $aAutoparts);
	}
}
