<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class RateController extends AppController {
	public $name = 'Rate';
	public $uses = array('Settings');

	public function refresh() {
		$this->autoRender = false;

		$aCurrency = array(
			'usd' => 840,
			'eur' => 978,
			'byr' => 974
		);
		App::uses('Rbc', 'Vendor');
		$today = new Rbc();
		$setKurs = array();
		$errMsg = '';
		try {

			$this->Settings->trxBegin();

			foreach($aCurrency as $curr => $code) {
				$kurs = floatval($today->curs($code));
				if ($kurs != Configure::read('Settings.xchg_'.$curr)) {
					$setKurs[$curr] = $kurs;
					$this->Settings->save(array('id' => 1, 'xchg_'.$curr => $kurs));
				}
				echo "$curr: $kurs<br/>";
			}

			$this->Settings->trxCommit();

		} catch (Exception $e) {
			$this->Settings->trxRollback();
			$errMsg = $e->getMessage();
			echo "Error! ".$errMsg;
		}

		if ($errMsg || $setKurs) {
			$Email = new CakeEmail();
			$Email->template('rates_refresh')->viewVars(compact('setKurs', 'errMsg'))
				->emailFormat('html')
				->from('info@' . Configure::read('domain.url'))
				->to(Configure::read('Settings.email'))
				->bcc(Configure::read('Settings.admin_email'))
				->subject(Configure::read('domain.title') . ': ' . __('Rates refreshing'))
				->send();
		}
	}
	
}
