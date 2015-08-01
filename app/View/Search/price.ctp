<? 
	$title = '';
	if (isset($errorText)) {
		$title = 'Ошибка!';
	} else {
		foreach($content as $row) {
			if ($row['title'] != '(БЕЗ НАЗВАНИЯ)' && $title = $row['title']) {
				break;
			}
		}
	}
	if (!$title) {
		$title = 'Результаты поиска';
	}
	echo $this->element('title', compact('title'));
?>
<div class="block clearfix">
<?
	if (isset($errorText)) {
?>
		<p class="error"><?=$errorText?></p>
<?
	} else {
		$this->Html->css('/Table/css/grid', array('inline' => false));
			
?>
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Производитель</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Номер</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Наименование</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Наличие</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Цена</a>
			</th>
<?
		if ($lFullInfo) {
?>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Провайдер</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Продавец</a>
			</th>
<?
		}
		$colspan = ($lFullInfo) ? 7 : 5;
?>
		</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="<?=$colspan?>" class="subheader">
					<b>Запрашиваемый номер и возможные замены (кроссы)</b>
				</td>
			</tr>
<?
		
		foreach($content as $row) {
?>
			<tr class="grid-row">
				<td>
					<?=($row['brand_logo']) ? $this->Html->image($row['brand_logo'], array('class' => 'brand-logo')) : ''?>
					<?=$row['brand'];?>
				</td>
				<td nowrap="nowrap"><?=$row['partnumber'];?></td>
				<td>
					<?=($row['image']) ? $this->Html->image($row['image'], array('class' => 'product-img')) : ''?>
					<?=$row['title']?><br/>
					<?=$row['title_descr']?>
				</td>
				<td>
					<b><?=$row['qty']?></b><br/>
					<?=$row['qty_descr']?>
				</td>
				<td align="right">
					<b><?=$this->Price->format($row['price2'])?></b>
<?
					if ($lFullInfo) {
?>
					<br/>
					Цена без/н.: <?=$this->Price->format($row['price'])?> (<?=$row['price_orig']?>)<br/>
					<?=$row['price_descr']?>
<?
					}
?>
				</td>
<?
					if ($lFullInfo) {
?>
				<td align="center"><?=($row['provider'] == 'Zzap') ? 'ZAP' : $row['provider']?></td>
				<td><?=$row['provider_descr']?></td>
<?
					}
?>
			</tr>
<?
		}
?>
		</tbody>
		</table>
		<br />
<?
	}
?>
</div>