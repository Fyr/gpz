<?=$this->element('admin_title', array('title' => __('Prices')))?>
<div class="span8 offset2">
<?
	echo $this->PHForm->create('Settings');
	// echo $this->element('admin_content');
?>
<?
	$aCurrency = array(
		'byr' => 'BYR Белорусские рубли',
		'rur' => 'RUR Российские рубли',
		'usd' => 'USD Доллары США',
		'eur' => 'EUR Евро'
	);
	$aTabs = array();
	foreach($aCurrency as $currency => $title) {
		$aTabs[strtoupper($currency)] = $this->PHForm->input('price_prefix_'.$currency, array(
				'class' => 'form-control input-small',
				'label' => array('text' => __('Price prefix'), 'class' => 'control-label')
			))
			.$this->PHForm->input('price_postfix_'.$currency, array(
				'class' => 'form-control input-small',
				'label' => array('text' => __('Price postfix'), 'class' => 'control-label')
			))
			.$this->PHForm->input('int_div_'.$currency, array(
				'class' => 'form-control input-small',
				'label' => array('text' => __('Thousands separator'), 'class' => 'control-label')
			))
			.$this->PHForm->input('decimals_'.$currency, array(
				'class' => 'form-control input-small',
				'label' => array('text' => __('Decimals'), 'class' => 'control-label')
			))
			.$this->PHForm->input('float_div_'.$currency, array(
				'class' => 'form-control input-small',
				'label' => array('text' => __('Decimal point'), 'class' => 'control-label')
			))
			.$this->PHForm->input('round_'.$currency, array(
				'class' => 'form-control input-small',
				'label' => array('text' => __('Round by'), 'class' => 'control-label')
			));
	}
	// echo $this->PHForm->input('price_curr', array('class' => 'input-large', 'options' => $options, 'label' => array('text' => 'Вывод цены в валюте', 'class' => 'control-label')));
	echo $this->element('admin_tabs', compact('aTabs'));
/*
	echo $this->PHForm->input('price_prefix', array('class' => 'input-small', 'label' => array('text' => 'Префикс', 'class' => 'control-label')));
	echo $this->PHForm->input('price_postfix', array('class' => 'input-small', 'label' => array('text' => 'Постфикс', 'class' => 'control-label')));
	echo $this->PHForm->input('int_div', array('class' => 'input-small', 'label' => array('text' => 'Разделитель разрядов', 'class' => 'control-label')));
*/
?>
<?
	// echo $this->element('admin_content_end');
	echo $this->element('Form.btn_save');
	echo $this->PHForm->end();
?>
</div>