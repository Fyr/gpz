<?php
App::uses('AppHelper', 'View/Helper');
class SiteRouterHelper extends AppHelper {

	public function url($article) {
		$objectType = $this->getObjectType($article);
		$aControllers = array(
			'SiteArticle' => 'Articles',
			'Product' => 'Products',
			'News' => 'News'
		);
		$controller = (isset($aControllers[$objectType])) ? $aControllers[$objectType] : 'Articles';
		$url = array('controller' => $controller, 'action' => 'view');
		if ($slug = $article[$objectType]['slug']) {
			// $url['objectType'] = $objectType;
			$url[] = $slug.'.html';
			// $url['ext'] = 'html';
		} else {
			$url[] = $article[$objectType]['id'];
		}
		
		return Router::url($url);
	}
	
}
