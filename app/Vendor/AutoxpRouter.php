<?php
App::uses('Router', 'Cake/Routing');
class AutoxpRouter extends Router {

	static public function url($aURL) {
		if (isset($aURL[1])) {
			$aURL[1] = str_replace('/', '|', $aURL[1]);
		}
		if (isset($aURL[4])) {
			$aURL[4] = urlencode($aURL[4]);
		}
		return Router::url($aURL);
	}
	
}
