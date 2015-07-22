<? 
	if (isset($errorText)) {
		$title = 'Ошибка!';
	} else {
		$title = '';
		foreach($aOfferTypeOptions as $offer_type => $offer_title) {
			if (isset($content[$offer_type])) {
				$brands = array_values($content[$offer_type]);
				$title = $brands[0][0]['title'];
				break;
			}
		}
		if (!$title) {
			$title = 'Результаты поиска';
		}
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
<style type="text/css">
.table-bordered th, .table-bordered td {
    border-left: 1px solid #dddddd;
}
.table-gradient {
    background-color: #3f6d70;
    background-image: linear-gradient(to bottom, #5a8f92 0%, #326263 100%);
}
</style>
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
?>
		</tr>
		</thead>
		<tbody>
<?
		$colspan = ($lFullInfo) ? 7 : 5;
		foreach($content as $descr_type => $brands) {
?>
			<tr>
				<td colspan="<?=$colspan?>" style="background: #ddd; padding: 10px 0 5px 10px;">
					<b><?=$aOfferTypeOptions[$descr_type]?></b>
				</td>
			</tr>
<?
			foreach($brands as $brand => $rows) {
				foreach($rows as $row) {
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
			}
		}
?>
		</tbody>
		</table>
		<br />
<?
	}
?>
</div>