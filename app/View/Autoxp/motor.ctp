<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoXP', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $mark['title'], 'url' => $this->Autoxp->url(array('action' => 'brand', $mark['id']))),
		array('label' => $model['title'], 'url' => $this->Autoxp->url(array('action' => 'model', $mark['id'], $model['id']))),
		array('label' => $body['title'], 'url' => $this->Autoxp->url(array('action' => 'bodytype', $mark['id'], $model['id'], $body['id']))),
		array('label' => $fuel['title'])
	)));
	
	$title = $mark['title'].' '.$model['title'].' '.$body['title'].' '.$fuel['title'];
	echo $this->element('title', compact('title'));
	echo $this->element('search_vin');
?>

<div class="block tableContent clearfix">
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Модель</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Год выпуска</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Обьем</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Мощность</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">КПП</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<?
	foreach($aModelsInfo as $row) {
?>
			<tr class="grid-row">
				<td><?=$row['model']?></td>
				<td align="center"><?=$row['date_issue']?></td>
				<td align="center"><?=number_format($row['volume'], 0, '', ',')?>м<sup>3</sup></td>
				<td align="center"><?=$row['kw']?> кВт / <?=$row['kw']?> л.с.</td>
				<td align="center"><?=$row['kpp']?></td>
				<td align="center">
					<?=$this->Autoxp->link('подробнее', array('action' => 'sections', $mark['id'], $model['id'], $body['id'], $fuel['id'], $row['hash'], $row['model']))?>
				</td>
			</tr>
<? 
	}
?>
		</tbody>
		</table>
</div>

