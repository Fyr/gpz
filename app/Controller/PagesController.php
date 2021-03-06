<?php
App::uses('AppController', 'Controller');
class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'CarType', 'News', 'Media.Media', 'TechDocApi', 'AutoxpApi');
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
		
		$aCarTypes = $this->CarType->find('all');
		$this->set('aCarTypes', $aCarTypes);
		$this->currMenu = 'Home';
		
		$marks = array(
			'AutoXP' => Hash::combine($this->AutoxpApi->getMarks(), '{n}.title', '{n}') 
		);
		$this->set('marks', $marks);
	}
	
	public function view($slug) {
		$article = $this->Page->findBySlug($slug);
		$this->set('article', $article);
		$this->seo = $article['Seo'];
		$this->currMenu = $slug;
	}
}
