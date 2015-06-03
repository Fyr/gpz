<?php
App::uses('AppHelper', 'View/Helper');
App::uses('SiteRouter', 'Vendor');
class ArticleVarsHelper extends AppHelper {
	public $helpers = array('Media', 'SiteRouter');

	public function init($article, &$url, &$title, &$teaser = '', &$src = '', $size = 'noresize', &$featured = false, &$id = '') {
		$objectType = SiteRouter::getObjectType($article);
		$id = $article[$objectType]['id'];
		
		$url = SiteRouter::url($article);
		
		$title = $article[$objectType]['title'];
		$teaser = nl2br($article[$objectType]['teaser']);
		$src = (isset($article['Media']) && $article['Media'] && isset($article['Media']['id']) && $article['Media']['id']) 
			? $this->Media->imageUrl($article, $size) : '';
		$featured = $article[$objectType]['featured'];
	}

	public function body($article) {
		return $article[SiteRouter::getObjectType($article)]['body'];
	}
}
