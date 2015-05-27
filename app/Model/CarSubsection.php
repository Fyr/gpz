<?php
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('Seo', 'Seo.Model');
class CarSubsection extends AppModel {
	
	var $hasOne = array(
		'Media' => array(
			'foreignKey' => 'object_id',
			'conditions' => array('Media.object_type' => 'CarSubsection', 'Media.main' => 1),
			'dependent' => true
		),
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'CarSubsection'),
			'dependent' => true
		)
	);
}
