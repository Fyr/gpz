<?=$this->element('admin_title', array('title' => __('Prices')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');
?>
<fieldset class="fieldset">
	<legend>Наценки</legend>
<?
	echo $this->PHForm->input('zz_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'Zzap, %', 'class' => 'control-label')));
	echo $this->PHForm->input('td_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'TecDoc, %', 'class' => 'control-label')));
	//echo $this->PHForm->input('pt_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'PartTrade, %', 'class' => 'control-label')));
	echo $this->PHForm->input('zt_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'ZapTrade, %', 'class' => 'control-label')));
?>
</fieldset>
<?
	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>