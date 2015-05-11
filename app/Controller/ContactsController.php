<?php
App::uses('AppController', 'Controller');
class ContactsController extends AppController {
	public $name = 'Contacts';
	public $uses = array('Page', 'Contact');
	public $components = array('Recaptcha.Recaptcha');
	public $helpers = array('Recaptcha.Recaptcha');

	public function index() {
		$article = $this->Page->findBySlug('contacts');
		$this->set('article', $article);
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$lCaptchaValid = $this->Recaptcha->verify();
			if (!$lCaptchaValid) {
				$this->set('recaptchaError', $this->Recaptcha->error);
			}
			if ($this->Contact->validates() && $lCaptchaValid) { // 
				$this->redirect(array('action' => 'success'));
			} else {
				// fdebug('inValid');
			}
		}
	}
	
	public function success() {
		$article = $this->Page->findBySlug('contacts');
		$this->pageTitle = $article['Page']['title'];
		// $this->set('article', $article);
	}

}
