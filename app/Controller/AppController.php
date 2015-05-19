<?php
App::uses('Controller', 'Controller');
class AppController extends Controller {
    public $paginate;
    public $components = array('RequestHandler');
	public $aNavBar = array(), $aBottomLinks = array(), $currMenu = '', $currLink = '', $pageTitle = '', 
		$seo, $aBreadCrumbs = array();
    
    public function __construct($request = null, $response = null) {
	    $this->_beforeInit();
	    parent::__construct($request, $response);
	    $this->_afterInit();
	}
	
	protected function _beforeInit() {
	    // Add here components, models, helpers etc that will be also loaded while extending child class
	}

	protected function _afterInit() {
	    // after construct actions here
	}
	
    public function isAuthorized($user) {
    	$this->set('currUser', $user);
		return Hash::get($user, 'active');
	}
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->beforeFilterLayout();
	}

	protected function beforeFilterLayout() {
		$this->loadModel('Settings');
		$this->Settings->initData();
		
		$this->aNavBar = array(
			'Home' => array('label' => __('Home'), 'href' => array('controller' => 'Pages', 'action' => 'home')),
			'News' => array('label' => __('News'), 'href' => array('controller' => 'News', 'action' => 'index')),
			'Products' => array('label' => __('Products'), 'href' => '/'),
			'Articles' => array('label' => __('Articles'), 'href' => array('controller' => 'Articles', 'action' => 'index')),
			'about-us' => array('label' => __('About us'), 'href' => array('controller' => 'Pages', 'action' => 'view', 'about-us.html')),
			'Contacts' => array('label' => __('Contacts'), 'href' => array('controller' => 'Contacts', 'action' => 'index'))
		);
		$this->aBottomLinks = $this->aNavBar;
		
		$this->currMenu = $this->_getCurrMenu();
	    $this->currLink = $this->currMenu;
	}

	protected function _getCurrMenu() {
		return strtolower($this->request->controller); // By default curr.menu is the same as controller name
	}

	public function beforeRender() {
		$this->set('aNavBar', $this->aNavBar);
		$this->set('currMenu', $this->currMenu);
		$this->set('aBottomLinks', $this->aBottomLinks);
		$this->set('currLink', $this->currLink);
		$this->set('pageTitle', $this->pageTitle);
		
		$this->beforeRenderLayout();
	}
	
	protected function beforeRenderLayout() {
		$this->set('seo', $this->seo);
		$this->set('aBreadCrumbs', $this->aBreadCrumbs);
	}

}
