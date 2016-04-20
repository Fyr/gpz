<?=$this->element('admin_title', array('title' => __('Exchange')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');
?>
<fieldset class="fieldset">
	<legend>Курсы валют, RUR (ЦБ РФ)</legend>
<?
	echo $this->PHForm->input('xchg_byr', array('class' => 'input-small', 'label' => array('text' => '10,000 BYR', 'class' => 'control-label')));
	echo $this->PHForm->input('xchg_usd', array('class' => 'input-small', 'label' => array('text' => 'USD', 'class' => 'control-label')));
	echo $this->PHForm->input('xchg_eur', array('class' => 'input-small', 'label' => array('text' => 'EUR', 'class' => 'control-label')));
?>
</fieldset>
<?
	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>