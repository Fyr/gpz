<?php
App::uses('AppHelper', 'View/Helper');
class SiteRouterHelper extends AppHelper {

	public function url($article) {
		$objectType = $this->getObjectType($article);
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
