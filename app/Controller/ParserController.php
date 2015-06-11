<?php
App::uses('AppController', 'Controller');
App::uses('Translit', 'Article.Vendor');

class ParserController extends AppController {
	public $name = 'Parser';
	public $uses = array('ZzapParser','CarType');
	
	public function index() {
		set_time_limit(6000);

			
			if(!isset($this->request->query['brand']) or !$this->request->query['brand']){
				echo 'Укажите производителя';exit();
			}
			
			$id = $this->CarType->field('CarType.id',array('CarType.slug'=>  Translit::convert($this->request->query['brand'],true)));
			if(!$id){
				echo 'Производитель не найден'; exit();
			}
		try{
			$this->ZzapParser->saveSubsections($id);
			echo 'all info saved succesfully';exit();
		}catch(Exception $e){
			echo 'Error: '.$e->getMessage();exit();
		}
			
	}
	
}
?>
