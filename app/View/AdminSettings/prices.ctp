<?=$this->element('admin_title', array('title' => __('Prices / Exchange')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	echo $this->element('admin_content');
?>
<fieldset class="fieldset">
	<legend>Наценки</legend>
<?
	echo $this->PHForm->input('price_ratio', array('class' => 'input-small', 'label' => array('text' => 'Zzap, %', 'class' => 'control-label')));
	echo $this->PHForm->input('td_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'TecDoc, %', 'class' => 'control-label')));
	echo $this->PHForm->input('pt_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'PartTrade, %', 'class' => 'control-label')));
	echo $this->PHForm->input('zt_price_ratio', array('class' => 'input-small', 'label' => array('text' => 'ZapTrade, %', 'class' => 'control-label')));
?>
</fieldset>
<fieldset class="fieldset">
	<legend>Курсы валют</legend>
<?
	echo $this->PHForm->input('xchg_rur', array('class' => 'input-small', 'label' => array('text' => 'RUR', 'class' => 'control-label')));
	echo $this->PHForm->input('xchg_usd', array('class' => 'input-small', 'label' => array('text' => 'USD', 'class' => 'control-label')));
	echo $this->PHForm->input('xchg_eur', array('class' => 'input-small', 'label' => array('text' => 'EUR', 'class' => 'control-label')));
?>
</fieldset>
<fieldset class="fieldset">
	<legend>Вывод цен\сумм</legend>
<?
	echo $this->PHForm->input('price_prefix', array('class' => 'input-small', 'label' => array('text' => 'Префикс', 'class' => 'control-label')));
	echo $this->PHForm->input('price_postfix', array('class' => 'input-small', 'label' => array('text' => 'Постфикс', 'class' => 'control-label')));
	echo $this->PHForm->input('int_div', array('class' => 'input-small', 'label' => array('text' => 'Разделитель разрядов', 'class' => 'control-label')));
?>
</fieldset>
<?
	echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>