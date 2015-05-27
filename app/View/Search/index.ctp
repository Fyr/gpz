<style type="text/css">
.table-bordered th, .table-bordered td {
    border-left: 1px solid #dddddd;
}
</style>
<?=$this->element('title', array('title' => 'Результаты поиска'))?>
<div class="block clearfix">
<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	if (!$output['result']) {
?>
		<p><?=$output['errorText']?></p>
<?
	} elseif (!count($output['content']['table'])) {
?>
		<p>Нет результатов</p>
<? 
	} else { 
?>
		<p>Найдено <?=count($output['content']['table'])?> результатов.</p> 
<?
		if (count($output['content']['table']) > 20) {
?>
		<p>Для более конкретного результата уточните поиск в поле запроса</p>
<?
		}
?>
		<table align="left" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Производитель</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Номер</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Имя</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Код позиции</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Изображение</a>
			</th>
			<th></th>
			<th></th>
		</tr>
		</thead>
		<tbody>
<? 
		foreach ($output['content']['table'] as $id => $row) {
?>
			<tr class="grid-row">
				<td><?=$row['class_man'];?></td>
				<td><?=$row['partnumber'];?></td>
				<td><?=$row['class_cat'];?></td>
				<td><?=$row['code_cat'];?></td>
				<td style="text-align:center;">
<?
			if ($row['imagepath']) {
?>
				<img src="<?=$row['imagepath'];?>" />
<?
			}
?>
				</td>
				<td class="priceCell" nowrap="nowrap">
					<!--<a href="/search/price/?number=<?=$row['partnumber']?>&classman=<?=$row['class_man'];?>" class="showPrice">подробнее</a>-->
					<span class="value">
<?					
						if(isset($row['price']) and $row['price']){
							$price = number_format($row['price'],0 ,"," ,Configure::read('Settings.int_div'));
							echo Configure::read('Settings.price_prefix').$price.Configure::read('Settings.price_postfix');
						}else{
							echo 'Нет предложений';
						}
?>
					</span>
				</td>
				<td>
					<a href="/Search/price?classman=<?=$row['class_man'];?>&number=<?=$row['partnumber'];?>">Подробнее</a>
				</td>
			</tr>
<? 
		}
?>
		</tbody>
		</table>	
<?
	}
?>
</div>