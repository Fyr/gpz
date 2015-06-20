<?php
class Prices {

	public static function calc($price) {
		$priceRatio = 1 + (Configure::read('Settings.price_ratio')/100);
		return round($priceRatio * Configure::read('Settings.xchg_rate') * $price, -2);
	}
	
	public static function format($price) {
		$price = number_format($price, 0, ',', Configure::read('Settings.int_div'));
		return Configure::read('Settings.price_prefix').$price.Configure::read('Settings.price_postfix');
	}
	
}
