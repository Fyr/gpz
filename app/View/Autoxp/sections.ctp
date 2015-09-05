<?
	$this->Html->css('zTreeStyle/zTreeStyle', array('inline' => false));
	$this->Html->script(array('vendor/jquery/jquery.ztree.core-3.5.min', 'mobile-panel'), array('inline' => false));
	
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
<div class="catalogPage">
	<div class="block leftSide">
<?
	foreach($subsections as $row) {
		$src = $this->Media->imageUrl($row, 'thumb80x80');
		if ($src) {
			$url = $this->Html->url(array('action' => 'subsections', $mark['id'], $model['id'], $body['id'], $fuel['id'], urlencode($hash), $row['AutoXP']['id']));
?>
		<a class="thumb-node" href="<?=$url?>" title="<?=h($row['Subsection']['title'])?>"><img src=<?=$src?> alt="<?=h($row['Subsection']['title'])?>" /></a>
<?
		}
	}
?>
	</div>
	<div class="block mainContentCatalog clearfix">
		<ul id="treeDemo" class="ztree">
<?
	foreach($aSubsections as $row) {
?>
			<li>
				<?=$this->Html->link($row['title'], array('action' => 'subsections', $mark['id'], $model['id'], $body['id'], $fuel['id'], urlencode($hash), $row['id']))?>
			</li>
<?
	}
?>
		</ul>
	</div>
</div>