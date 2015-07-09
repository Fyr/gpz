<? 
	if (isset($content['header'])){ 
		echo $this->element('title', array('title' => $content['header']));
	}
?>
<div class="block clearfix">
<?
	if (isset($errorText)) {
?>
		<p class="error"><?=$errorText?></p>
<?
	} else {
		// $price = (isset($content['price_min']) && $content['price_min']) ? $this->Price->format($content['price_min']) : 'Нет предложений';
		// $price = $this->Price->format($content['price']);
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
/*
.grid .grid-row:nth-child(2n+1) td {
    background: none repeat scroll 0 0 #edfefe;
    border-left: 1px solid #fff;
}
*/
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
				<a class="grid-unsortable" href="javascript:void(0)">Продавец</a>
			</th>
<?
		}
?>
		</tr>
		</thead>
		<tbody>
<?
		$colspan = ($lFullInfo) ? 6 : 5;
		foreach($content['table'] as $descr_type => $brands) {
?>
			<tr>
				<td colspan="<?=$colspan?>" style="background: #ddd; padding: 10px 0 5px 0;">
					<b><?=$descr_type?></b>
				</td>
			</tr>
<?
			foreach($brands as $brand => $rows) {
				foreach($rows as $row) {
?>
			<tr class="grid-row">
				<td>
					<?=($row['logopath']) ? $this->Html->image($row['logopath']) : ''?>
					<?=$row['class_man'];?>
				</td>
				<td><?=$row['partnumber'];?></td>
				<td>
					<?=($row['imagepath']) ? $this->Html->image($row['imagepath'], array('style' => 'float: left; margin: 0 5px 5px 0')) : ''?>
					<?=$row['class_cat']?>
				</td>
				<td>
					<b><?=$row['qty']?></b><br/>
					<?=$row['descr_qty']?>
				</td>
				<td align="right">
<?
					if ($lFullInfo) {
?>
					<b><?=$row['price']?></b>(<?=$this->Price->format($row['price_clean'])?>)<br/>
					<?=$row['descr_price']?>
<?
					} else {
						echo '<b>'.$this->Price->format($row['price_clean']).'</b>';
					}
?>
				</td>
<?
					if ($lFullInfo) {
?>
				<td>
					<?=$row['class_user']?><br />
					<?=$row['descr_address']?><br />
					<?=$row['phone1']?>
				</td>
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