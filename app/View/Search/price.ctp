<? 
	if (isset($content)){  
		echo $this->element('title', array('title' => $content['class_cat'].' '.$content['partnumber']));
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
			<td><?=$this->Price->format($content['price'])?></td>	
		</tr>
	
	</table>
<?
	}
?>
</div>