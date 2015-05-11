<?php
App::uses('AppModel', 'Model');
App::uses('Article', 'Article.Model');
App::uses('Seo', 'Seo.Model');
class SubcategoryArticle extends Article {
	protected $objectType = 'SubcategoryArticle';
	
	var $belongsTo = array(
		'CategoryArticle' => array(
			'className' => 'Article.Article',
			'foreignKey' => 'cat_id',
			'conditions' => array('CategoryArticle.object_type' => 'CategoryArticle'),
			'dependent' => true
		)
	);
	
	var $hasOne = array(
		'Seo' => array(
			'className' => 'Seo.Seo',
			'foreignKey' => 'object_id',
			'conditions' => array('Seo.object_type' => 'SubcategoryArticle'),
			'dependent' => true
		)
	);
	
}
