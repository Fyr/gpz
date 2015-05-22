<?php
App::uses('AppController', 'Controller');

class ParserController extends AppController {
	public $name = 'Parser';
	public $uses = array('ZzapParser');
	
	public function index() {
		try{	
			$this->ZzapParser->saveSubsections();
			echo 'all info saved succesfully';exit();
		}catch(Exception $e){
			echo 'Error: '.$e->getMessage();exit();
		}
			
	}
	
}
?>
