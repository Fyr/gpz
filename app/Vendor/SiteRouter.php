<?php
App::uses('Router', 'Cake/Routing');
class SiteRouter extends Router {

	static public function getObjectType($article) {
		list($objectType) = array_keys($article);
		return $objectType;
	}
	
	static public function url($article = null, $full = false) {
		$objectType = self::getObjectType($article);
		$aControllers = array(
			'SiteArticle' => 'Articles',
			'Product' => 'Products',
			'News' => 'News',
			'CarType' => 'Car'
		);
		$controller = (isset($aControllers[$objectType])) ? $aControllers[$objectType] : 'Articles';
		if ($objectType == 'CarType') {
			return Router::url(array('controller' => 'Car', 'action' => 'viewCarType', 'carType' => $article['CarType']['slug'])).'/';
		} elseif ($objectType == 'CarSubtype') {
			return Router::url(array('controller' => 'Car', 'action' => 'view', 'carType' => $article['CarType']['slug'], 'carSubtype' => $article['CarSubtype']['slug'])).'/';	
		} elseif ($objectType == 'CarSubsection') {
			return Router::url(array('controller' => 'Search', 'action' => 'index', 'carType' => $article['CarType']['slug'], 'carSubtype' => $article['CarSubtype']['slug'], 'slug' => $article['CarSubsection']['slug'], 'ext' => 'html'));	
		} else {
			$url = array('controller' => $controller, 'action' => 'view');
		}
		if ($slug = $article[$objectType]['slug']) {
			$url[] = $slug.'.html';
		} else {
			$url[] = $article[$objectType]['id'];
		}
		
		return Router::url($url);
	}
	
}
