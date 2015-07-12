<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	/*
	$title = 'Результаты поиска';
		$title = $article['CarSubsection']['title'];
		$carType = array('CarType' => $article['CarType']);
		$carSubtype = array('CarSubtype' => $article['CarSubtype'], 'CarType' => $article['CarType']);
		echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
			array('label' => $article['CarType']['title'], 'url' => SiteRouter::url($carType)),
			array('label' => $article['CarSubtype']['title'], 'url' => SiteRouter::url($carSubtype)),
			array('label' => $title)
		)));
	echo $this->element('title', compact('title'));
	*/
	$mark_id = $this->request->pass[0];
	$model_id = $this->request->pass[1];
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
<div class="block tableContent clearfix">

		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Модификация</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Цилиндры</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Выпуск</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Код двигателя</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Кузов</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
		foreach ($aSubModels as $row) {
			$release = substr($row['year_from'], 0, 4).'/'.substr($row['year_from'], 4);
			if (isset($row['year_to'])) {
				$release.= ' - '.substr($row['year_to'], 0, 4).'/'.substr($row['year_to'], 4);
			}
?>
			<tr class="grid-row">
				<td><?=$row['type']?></td>
				<td align="center"><?=$row['cylinders']?> / <?=$row['vcm']?>m<sup>3</sup></td>
				<td align="center"><?=$release?></td>
				<td><?=$row['engine_code']?> <?=$row['engine_name']?></td>
				<td align="center"><?=$row['body_name']?></td>
				<td align="center">
					<?=$this->Html->link('подробнее', array('action' => 'subsections', $mark_id, $model_id, $row['id']))?>
				</td>
			</tr>
<? 
		}
?>
		</tbody>
		</table>
		<br />
</div>
