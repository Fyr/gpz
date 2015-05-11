<?php
App::uses('AppController', 'Controller');
class NewsController extends AppController {
	public $name = 'News';
	public $uses = array('SiteArticle', 'News');
	public $helpers = array('ObjectType');
	
	const PER_PAGE = 10;
	
	protected $objectType;

	public function beforeFilter() {
		$this->objectType = 'News';
		
		parent::beforeFilter();
	}
	
	public function beforeRender() {
		$this->currMenu = ($this->objectType == 'News') ? 'News' : 'Articles';
		$this->set('objectType', $this->objectType);
		
		parent::beforeRender();
	}
	
	public function index() {
		$this->paginate = array(
			'conditions' => array($this->objectType.'.published' => 1),
			'limit' => self::PER_PAGE, 
			'order' => $this->objectType.'.created DESC',
			'page' => $this->request->param('page')
		);
		$this->set('aArticles', $this->paginate($this->objectType));
		
		// $this->aBreadCrumbs = array(__('Home page') => '/', )
	}
	
	public function view($slug) {
		if (is_numeric($slug)) {
			$article = $this->{$this->objectType}->findById($slug);
		} else {
			$article = $this->{$this->objectType}->findBySlug($slug);
		}
		
		if (!$article && !TEST_ENV) {
			return $this->redirect('/');
		}
		
		$this->set('article', $article);
		$this->seo = $article['Seo'];
	}
}
