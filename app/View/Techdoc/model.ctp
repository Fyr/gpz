<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'TecDoc', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $mark['title'], 'url' => array('controller' => 'Techdoc', 'action' => 'brand', $mark['id'])),
		array('label' => $model['title'])
	)));
	
	$title = $mark['title'].' '.$model['title'].' '.$model['date_issue'];
	echo $this->element('title', compact('title'));
	
	$mark_id = $this->request->pass[0];
	$model_id = $this->request->pass[1];
?>
<div class="block tableContent clearfix">
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Мотор</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Год выпуска</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Тип двигателя</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Наименование двигателя</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Кузов</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Цилиндры</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Мощность</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
	$motors = array();
	foreach ($aSubModels as $row) {
		$motors[$row['type']][] = $row;
	}
	foreach($motors as $type => $aSubModels) {
?>
			<tr class="grid-row">
				<td colspan="9" class="subheader">
					<?=$type?>
				</td>
			</tr>
<?
		foreach ($aSubModels as $row) {
?>
			<tr class="grid-row">
				<td><?=$row['type']?></td>
				<td align="center"><?=$row['date_issue']?></td>
				<td align="center"><?=$row['engine_code']?></td>
				<td><?=$row['engine_name']?></td>
				<td align="center"><?=$row['body_name']?></td>
				<td align="center"><?=$row['cylinders']?> / <?=number_format($row['vcm'], 0, '', ',')?>м<sup>3</sup></td>
				<td align="center"><?=$row['kw_from']?> кВт / <?=$row['hp_from']?> л.с</td>
				<td align="center">
					<?=$this->Html->link('подробнее', array('action' => 'subsections', $mark_id, $model_id, $row['id']))?>
				</td>
			</tr>
<? 
		}
	}
?>
		</tbody>
		</table>
</div>

