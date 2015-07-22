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
<div class="block tableContent clearfix">
<?
	if (isset($errorText)) {
?>
		<p class="error"><?=$errorText?></p>
<?
	} elseif (isset($content)) {
		if (!isset($article)) {
?>
		<p>Найдено <?=count($content)?> результатов.</p> 
<?
			if (count($content) > 20) {
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
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
		foreach ($content as $row) {
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
					<a class="showLoader" href="/Search/price?brand=<?=$row['brand'];?>&number=<?=$row['partnumber'];?>">Цены и замены</a>
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