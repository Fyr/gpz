<?=$this->element('title', array('title' => $output['content']['class_cat'].' '.$output['content']['partnumber']))?>
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
			<td><?=(!$output['content']['price']) ? 'Нет предложений' : $output['content']['price'].' р.'?></td>	
		</tr>
	</table>
<?
	}
?>
</div>