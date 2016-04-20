<?php
App::uses('AdminController', 'Controller');
class AdminSettingsController extends AdminController {
    public $name = 'AdminSettings';
    public $uses = array('Settings', 'Section');
    
    public function beforeFilter() {
		if (!$this->isAdmin()) {
			$this->redirect(array('controller' => 'Admin', 'action' => 'index'));
			return;
		}
		parent::beforeFilter();

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data('Settings.id', 1);
            $this->Settings->save($this->request->data);
            $this->setFlash(__('Settings are saved'), 'success');
            $this->redirect(array('action' => $this->request->action));
            return;
        }
        $this->request->data = $this->Settings->getData();
	}

    public function index() {
    }

    public function contacts() {
    }
    
    public function prices() {
    }

    public function exchange() {
    }
    
    public function markup() {
    }
}
