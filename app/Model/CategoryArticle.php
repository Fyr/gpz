<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
class CategoryArticle extends Article {
	protected $objectType = 'CategoryArticle';
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'CategoryArticle'),
			'dependent' => true
		)
	);
	
}
