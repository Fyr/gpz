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
		<div align="right" style="margin-bottom: 10px;">
			Сортировать по <?=$this->Form->input('sort', array('options' => $aSorting, 'value' => $sort, 'div' => false, 'label' => false))?>
			<?=$this->Form->input('order', array('options' => $aOrdering, 'value' => $order, 'div' => false, 'label' => false))?>
		</div>

		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th></th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Лого</a>
			</th>
<?
		foreach($aSorting as $key => $title) {
			$class = ($sort == $key) ? 'grid-sortable-active grid-sortable-'.$order : '';
			$dir = ($sort == $key && $order == 'asc') ? 'desc' : 'asc';
?>
			<th>
				<a class="grid-sortable <?=$class?>" href="javascript:void(0)" title="Сортировать по `<?=$title?>` <?=$aOrdering[$dir]?>" onclick="sortBy('<?=$key?>', '<?=$dir?>')"><?=$title?></a>
			</th>
<?
		}
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
		$colspan = ($lFullInfo) ? 9 : 7;
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
				</td>
				<td>
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

<script type="text/javascript">
function sortBy(key, dir) {
	var url = location.href;
	if (url.indexOf('sort') > -1) {
		url = url.replace(/sort=\w+/, 'sort=' + key);
	} else {
		url+= '&sort=' + key;
	}
	if (url.indexOf('order') > -1) {
		url = url.replace(/order=\w+/, 'order=' + dir);
	} else {
		url+= '&order=' + dir;
	}
	
	location.href = url;
}

$(function(){
	$('#sort, #order').change(function(){
		sortBy($('#sort').val(), $('#order').val());
	});
});
</script>