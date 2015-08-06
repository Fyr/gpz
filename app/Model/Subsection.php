<?php
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
class Subsection extends AppModel {
	
	var $hasOne = array(
		'Media' => array(
			'className' => 'Media.Media',
			'foreignKey' => 'object_id',
			'conditions' => array('Media.object_type' => 'Subsection', 'Media.main' => 1),
			'dependent' => true
		)
	);
	
	/**
	 * Сохранить список осн.узлов без дублирования
	 *
	 * @param mixed $aSubsections
	 */
	public function saveMainSubsections($aSubsections) {
		$ids = Hash::extract($aSubsections, '{n}.id');
		$fields = array('td_id', 'title');
		$conditions = array('td_id' => $ids);
		$ids = array_keys($this->find('list', compact('fields', 'conditions')));
		foreach($aSubsections as $row) {
			if ($row['parent'] == 10001 && !in_array($row['id'], $ids)) { // признак root-узла
				$this->clear();
				$this->save(array('title' => $row['name'], 'td_id' => $row['id']));
			}
		}
	}
}
