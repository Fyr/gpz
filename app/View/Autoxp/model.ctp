<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoXP', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $mark['title'], 'url' => array('controller' => 'Autoxp', 'action' => 'brand', $mark['id'])),
		array('label' => $model['title'])
	)));
	
	$title = $mark['title'].' '.$model['title'];
	echo $this->element('title', compact('title'));
	echo $this->element('search_vin');
?>
<div class="block tableContent clearfix">
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
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
	foreach($aBodyTypes as $row) {
?>
			<tr class="grid-row">
				<td><?=$row['title']?></td>
				<td align="center">
					<?=$this->Html->link('подробнее', array('action' => 'bodytype', $mark['id'], $model['id'], $row['id']))?>
				</td>
			</tr>
<? 
	}
?>
		</tbody>
		</table>
</div>

