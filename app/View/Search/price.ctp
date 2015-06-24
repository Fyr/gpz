<? 
	if (isset($content)){ 
		if (isset($content['table']) && is_array($content['table'])) {
			echo $this->element('title', array('title' => $content['table'][0]['class_cat'].' '.$content['table'][0]['partnumber']));
		} else {
			echo $this->element('title', array('title' => $content['class_cat'].' '.$content['partnumber']));
		}
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
		if (isset($content['table']) && is_array($content['table'])) {
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
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Продавец</a>
			</th>
		</tr>
		</thead>
		<tbody>
<?
			foreach($content['table'] as $row) {
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
					<b><?=$row['price']?></b><br/>
					<?=$row['descr_price']?>
				</td>
				<td>
					<?=$row['class_user']?><br />
					<?=$row['descr_address']?><br />
					<?=$row['phone1']?>
				</td>
			</tr>
<?
			}
?>
		</tbody>
		</table>
		<br />
<?
		} else {
			$src = ($content['imagepath']) ? $content['imagepath'] : '';
			if ($src) {
				echo $this->Html->image($content['imagepath'], array(
					'alt' => $content['class_cat'].' '.$content['partnumber'],
					'class' => 'pull-right'
				)).'<br/>';
			}
?>
	<table id="itemTable">
		<tr>
			<td class="header">Наименование:</td>
			<td><?=$content['class_cat']?></td>	
		</tr>
		<tr>
			<td class="header">Номер Детали:</td>
			<td><?=$content['partnumber']?></td>	
		</tr>
		<tr>
			<td class="header">Производитель:</td>
			<td><?=$content['class_man']?></td>	
		</tr>
		<tr>
			<td class="header">Срок поставки</td>
			<td><?=$content['descr_qty']?></td>
		</tr>
		<tr>
			<td class="header">Цена</td>
			<td><?=$this->Price->format($content['price_clean'])?></td>	
		</tr>
	
	</table>
<?
		}
	}
?>
</div>