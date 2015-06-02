<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	$title = 'Результаты поиска';
	if (isset($errorText)) {
		$title = 'Ошибка!';
	} elseif (isset($article)) {
		$title = $article['CarSubsection']['title'];
		$carType = array('CarType' => $article['CarType']);
		$carSubtype = array('CarSubtype' => $article['CarSubtype'], 'CarType' => $article['CarType']);
		echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
			array('label' => $article['CarType']['title'], 'url' => SiteRouter::url($carType)),
			array('label' => $article['CarSubtype']['title'], 'url' => SiteRouter::url($carSubtype)),
			array('label' => $title)
		)));
	}
	echo $this->element('title', compact('title'));
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
<div class="block tableContent clearfix">
<?
	if (isset($errorText)) {
?>
		<p class="error"><?=$errorText?></p>
<?
	} elseif (isset($content) && isset($content['table']) && $content['table']) {
		if (!isset($article)) {
?>
		<p>Найдено <?=count($content['table'])?> результатов.</p> 
<?
			if (count($content['table']) > 20) {
?>
		<p>Для более конкретного результата уточните поиск в поле запроса</p>
<?
			}
		}
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
				<a class="grid-unsortable" href="javascript:void(0)">Код позиции</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Изображение</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Цена</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
		foreach ($content['table'] as $id => $row) {
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
					<a class="showLoader" href="/Search/price?classman=<?=$row['class_man'];?>&number=<?=$row['partnumber'];?>">Подробнее</a>
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
		echo '<p>По данному запросу результатов не найдено</p>';
	}
?>
</div>
<?
	if (isset($article)) {
		echo $this->ArticleVars->body($article);
	}
?>