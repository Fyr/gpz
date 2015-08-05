<? 
	$this->Html->css(array('/Icons/css/icons', 'the-modal'), array('inline' => false));
	$this->Html->script('vendor/jquery/jquery.the-modal', array('inline' => false));
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
<div class="block tableContent clearfix">
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
			<th></th>
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
		$colspan = ($lFullInfo) ? 8 : 6;
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
		foreach($content as $i => $row) {
?>
			<tr class="grid-row">
				<td>
<?
			if ($row['provider'] == 'TechDoc' && isset($row['provider_data']['criteria']) && $params = $row['provider_data']['criteria']) {
?>
					<a class="icon-color icon-info popup-trigger" href="javascript:void(0)" onclick="$('#partnumber<?=$i?>').modal().open();"></a>
					<div class="modal" id="partnumber<?=$i?>" style="display: none">
						<span class="popup-close">&times;</span>
						<h3><?=$row['partnumber']?> <?=$row['title']?> </h3>
						<div align="center" style="margin: 10px 0">
							<?=($row['image']) ? $this->Html->image($row['image'], array('alt' => h($row['title']), 'style' => 'max-width: 75%')) : ''?>
						</div>
						<b>Технические характеристики</b><br/>
<?
				foreach($params as $param) {
?>
						<?=$param['key']?>: <?=$param['value']?><br/>
<?
				}
?>
					</div>
<?
			}
?>
				</td>
				<td>
					<?=($row['brand_logo']) ? $this->Html->image($row['brand_logo'], array('class' => 'brand-logo')) : ''?>
					<?=$row['brand'];?>
				</td>
				<td nowrap="nowrap">&nbsp;<?=$row['partnumber']?></td>
				<td>
					<?//($row['image']) ? $this->Html->image($row['image'], array('class' => 'product-img')) : ''?>
					<?=$row['title']?>
					<?//$row['title_descr']?>
				</td>
				<td>
					<b><?=$row['qty']?></b>
					<?=(trim($row['qty_descr'])) ? $row['qty_descr'].'<br/>' : ''?>
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
<?
	}
?>
</div>

