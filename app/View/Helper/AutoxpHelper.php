<?php
App::uses('AppHelper', 'View/Helper');
App::uses('AutoxpRouter', 'Vendor');
class AutoxpHelper extends AppHelper {
	public $helpers = array('Html');
	
	public function url($aURL) {
		return AutoxpRouter::url($aURL);
	}
	
	public function link($title, $aURL) {
		return $this->Html->link($title, $this->url($aURL));
	}
}
