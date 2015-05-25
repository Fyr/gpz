<? if(isset($output['content'])){  
	echo $this->element('title', array('title' => $output['content']['class_cat'].' '.$output['content']['partnumber']));
}?>
<div class="block clearfix">
<?
	if (!$output['result']) {
?>
	<p><?=$output['errorText']?></p>
<?
	} else {
?>
	<table id="itemTable">
		<tr>
			<td class="header">Наименование:</td>
			<td><?=$output['content']['class_cat']?></td>	
		</tr>
		<tr>
			<td class="header">Номер Детали:</td>
			<td><?=$output['content']['partnumber']?></td>	
		</tr>
		<tr>
			<td class="header">Производитель:</td>
			<td><?=$output['content']['class_man']?></td>	
		</tr>
		<tr>
			<td class="header">Изображение:</td>
			<td><?=($output['content']['imagepath']) ? $this->Html->image($output['content']['imagepath']) : 'Нет изображения'?></td>
		</tr>
		<tr>
			<td class="header">Цена</td>
			<td><?php if(!$output['content']['price']) { 
					echo 'Нет предложений';
				}else{
					$price = number_format($output['content']['price'],0 ,"," ,Configure::read('Settings.int_div'));
					echo Configure::read('Settings.price_prefix').$price.Configure::read('Settings.price_postfix');
				}?>
		</td>	
		</tr>
	</table>
<?
	}
?>
</div>