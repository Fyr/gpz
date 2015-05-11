<?php if((isset($result['error']) and $result['error']) or $error){?>
	<p>Произошла ошибка <?php if($result['error']){echo $result['error'];}?></p>
<?php }else{
	if(!count($result['table'])){?>
		<p>Нет результатов</p>
	<?php }else{ ?>
		<p> Найдено <?php echo count($result['table']);?> результатов.</p> 
		<p>Для более точного результата уточните поиск в поле запроса</p>
		<table>
		<tr>
			<th>Производитель</th>
			<th>Номер</th>
			<th>Имя</th>
			<th>Код позиции</th>
			<th>Изображение</th>
			<th></th>
		</tr>
		<?php foreach($result['table'] as $id=>$row){?>
			<tr>
				<td><?php echo $row['class_man'];?></td>
				<td><?php echo $row['partnumber'];?></td>
				<td><?php echo $row['class_cat'];?></td>
				<td><?php echo $row['code_cat'];?></td>
				<td><img src="<?php echo $row['imagepath'];?>" /></td>
				<td class="priceCell">
					<a href="javascript:void(0);" class="showPrice" data-number="<?php echo $row['partnumber'];?>" data-classman="<?php echo $row['class_man'];?>">Посмотреть цены</a>
					<img style="display:none;" src="/img/spinner.gif">
					<span class="value"></span>
				</td>
			</tr>
		<?php }?>
		</table>	
	<?php }?>
<?php }?>