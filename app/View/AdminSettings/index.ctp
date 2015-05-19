<?=$this->element('admin_title', array('title' => __('Settings')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');
	echo $this->PHForm->input('admin_email', array('class' => 'input-large'));
	echo $this->PHForm->input('office_address');
	echo $this->PHForm->input('phone1', array('class' => 'input-large'));
	echo $this->PHForm->input('phone2', array('class' => 'input-large'));
	echo $this->PHForm->input('skype', array('class' => 'input-large'));
	echo $this->PHForm->input('price_ratio', array('class' => 'input-large', 'label' => array('text' => 'Наценка, %', 'class' => 'control-label')));
	echo $this->PHForm->input('xchg_rate', array('class' => 'input-large', 'label' => array('text' => 'Курс', 'class' => 'control-label')));
	echo $this->PHForm->input('price_prefix', array('class' => 'input-large', 'label' => array('text' => 'Префикс цены', 'class' => 'control-label')));
	echo $this->PHForm->input('price_postfix', array('class' => 'input-large', 'label' => array('text' => 'Постфикс цены', 'class' => 'control-label')));
	echo $this->PHForm->input('int_div', array('class' => 'input-large', 'label' => array('text' => 'Разделитель разрядов', 'class' => 'control-label')));
	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>