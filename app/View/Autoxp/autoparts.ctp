<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoXP', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $mark['title'], 'url' => array('controller' => 'Autoxp', 'action' => 'brand', $mark['id'])),
		array('label' => $model['title'], 'url' => array('controller' => 'Autoxp', 'action' => 'model', $mark['id'], $model['id'])),
		array('label' => $body['title'], 'url' => array('controller' => 'Autoxp', 'action' => 'bodytype', $mark['id'], $model['id'], $body['id'])),
		array('label' => $fuel['title'])
	)));
	
	$title = $mark['title'].' '.$model['title'].' '.$body['title'].' '.$fuel['title'];
	echo $this->element('title', compact('title'));
	echo $this->element('search_vin');
?>
<div class="block tableContent clearfix">
		<div align="center">
			<?=$aAutoparts['image_map']?>
			<?=$this->Html->image($subsection['img'], array('alt' => $subsection['title'], 'usemap' => '#picmain'))?>
		</div>
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">N на рис.</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Название</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Номер</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
		foreach ($aAutoparts['items'] as $row) {
			$q = $row['detail_num'];
?>
			<tr id="<?=$row['tr_id']?>" class="grid-row">
				<td align="center"><?=$row['npp']?></td>
				<td><?=$row['title']?></td>
				<td align="center"><?=$row['detail_num']?></td>
				<td nowrap="nowrap" align="center">
					<?=$this->Html->link('Цены и замены', array('controller' => 'Search', 'action' => 'index', '?' => compact('q')))?>
				</td>
			</tr>
<? 
		}
?>
		</tbody>
		</table>
</div>
<script type="text/javascript">
function SelectRow(pos) {
	$('.grid tr').removeClass('grid-row-selected');
	$('#' + pos + '_0').get(0).scrollIntoView(true);
	$('#' + pos + '_0').addClass('grid-row-selected');
}
</script>