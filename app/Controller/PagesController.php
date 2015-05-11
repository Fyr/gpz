<?php
App::uses('AppController', 'Controller');
class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'Product', 'News', 'Media.Media');
	// public $helpers = array('ArticleVars');

	public function home() {
		// Welcome block
		$article = $this->Page->findBySlug('home');
		$this->set('article', $article);
		$this->seo = $article['Seo'];
		
		// Новости
		$conditions = array('News.published' => 1);
		$order = array('News.created' => 'DESC');
		$limit = 3;
		$news = $this->News->find('all', compact('conditions', 'order', 'limit'));
		$this->set('news', $news);
		
		/*
		$conditions = array('object_type' => 'Slider');
		$order = 'created';
		$aSlider = $this->Media->find('all', compact('conditions', 'order'));
		*/
		// $this->set('aSlider', $this->Media->getObjectList('Slider'));
		$this->currMenu = 'Home';
	}
	
	public function view($slug) {
		$article = $this->Page->findBySlug($slug);
		$this->set('article', $article);
		$this->seo = $article['Seo'];
		$this->currMenu = $slug;
	}
}
