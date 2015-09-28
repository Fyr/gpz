<?php
App::uses('AppHelper', 'View/Helper');
class AutoxpHelper extends AppHelper {
	public $helpers = array('Html');
	
	public function url($aURL) {
		if (isset($aURL[1])) {
			$aURL[1] = str_replace('/', '|', $aURL[1]);
		}
		if (isset($aURL[4])) {
			$aURL[4] = urlencode($aURL[4]);
		}
		return $this->Html->url($aURL);
	}
	
	public function link($title, $aURL) {
		return $this->Html->link($title, $this->url($aURL));
	}
}
