<?
	$this->Html->css(array('/Table/css/grid', 'jquery.fancybox'), array('inline' => false));
	$this->Html->script(array('vendor/jquery/jquery.fancybox.pack'), array('inline' => false));
	
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
				<a class="grid-unsortable" href="javascript:void(0)">Фото</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Название</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
	foreach($aSubsections as $row) {
?>
			<tr class="grid-row">
				<td align="center">
					<a class="fancybox" href="<?=$row['img']?>" rel="photo">
						<?=$this->Html->image($row['thumb'], array('alt' => $row['title']))?>
					</a>
				</td>
				<td><?=$row['title']?></td>
				<td align="center">
					<?=$this->Autoxp->link('подробнее', array('action' => 'autoparts', $mark['id'], $model['id'], $body['id'], $fuel['id'], $hash, $row['grnum'], $row['pdgrnum']))?>
				</td>
			</tr>
<? 
	}
?>
		</tbody>
		</table>
</div>

<script type="text/javascript">
$(function(){
	$('.fancybox').fancybox({
		padding: 5
	});
});
</script>