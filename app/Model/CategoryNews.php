<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
class CategoryNews extends Article {
	protected $objectType = 'CategoryNews';
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'CategoryNews'),
			'dependent' => true
		)
	);
	
}
