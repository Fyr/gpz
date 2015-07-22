<?php
App::uses('AppHelper', 'View/Helper');
class PriceHelper extends AppHelper {
	
	public function format($price) {
		$price = number_format($price, 0, '.', Configure::read('Settings.int_div'));
		return Configure::read('Settings.price_prefix').$price.Configure::read('Settings.price_postfix');
	}
}
