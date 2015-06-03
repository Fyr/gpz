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
			<td class="header">Изображение:</td>
			<td><?=($content['imagepath']) ? $this->Html->image($content['imagepath']) : 'Нет изображения'?></td>
		</tr>
		<tr>
			<td class="header">Срок поставки</td>
			<td><?php if(!$content['shipping']) { 
					echo '-';
				}else{	
					echo $content['shipping'];
				}?>
		</td>
		</tr>

		<tr>
			<td class="header">Цена</td>
			<td><?php if(!$content['price']) { 
					echo 'Нет предложений';
				}else{
					$price = number_format($content['price'],0 ,"," ,Configure::read('Settings.int_div'));
					echo Configure::read('Settings.price_prefix').$price.Configure::read('Settings.price_postfix');
				}?>
		</td>	
		</tr>
	
	</table>
<?
	}
?>
</div>