<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Prices', 'Vendor');
class PriceHelper extends AppHelper {
	
	public function format($price) {
		return Prices::format(Prices::calc($price));
	}
}
