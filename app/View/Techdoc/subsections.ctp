<?
	$this->Html->css('zTreeStyle/zTreeStyle', array('inline' => false));
	$this->Html->script(array('vendor/jquery/jquery.ztree.core-3.5.min', 'mobile-panel'), array('inline' => false));
	
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'TecDoc', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $mark['title'], 'url' => array('controller' => 'Techdoc', 'action' => 'brand', $mark['id'])),
		array('label' => $model['title'], 'url' => array('action' => 'model', $mark['id'], $model['id'])),
		array('label' => $submodel['type'])
	)));
	
	$title = $mark['title'].' '.$model['title'].' '.$submodel['type'].' '.$model['date_issue'];
	echo $this->element('title', compact('title'));
	
	$aParent = Hash::extract($aSubsections, '{n}.parent');
	foreach($aSubsections as &$item) {
		if (!in_array($item['id'], $aParent)) {
			$params = $this->request->pass;
			$params['action'] = 'autoparts';
			$params[] = $item['id'];
			$item['url'] = $this->Html->url($params);
			$item['target'] = '_self';
		}
	}
?>
<style type="text/css">
.ztree li span.button.ico_docu { display: none; }
</style>
<div class="catalogPage">
	<div class="block leftSide">
<?
	foreach($subsections as $row) {
		$src = $this->Media->imageUrl($row, 'thumb80x80');
		if ($src) {
?>
		<a class="thumb-node" href="javascript:void(0)" title="<?=h($row['Subsection']['title'])?>" onclick="expandNode(<?=$row['Subsection']['td_id']?>)"><img src=<?=$src?> alt="<?=h($row['Subsection']['title'])?>" /></a>
<?
		}
	}
?>
	</div>
	<div class="block mainContentCatalog clearfix">
		<ul id="treeDemo" class="ztree"></ul>
	</div>
</div>
<script type="text/javascript">
var treeObj;
function expandNode(id) {
	var node = treeObj.getNodeByParam("id", id, null);
	$('#' + node.tId).get(0).scrollIntoView();
	closeOpenPanel();
	treeObj.expandNode(node, true);
}
	
$(document).ready(function(){
	var setting = {
		view: {
			selectedMulti: false,
			txtSelectedEnable: false,
			showIcon: function(treeId, treeNode) {
				return !treeNode.isParent;
			}
		},
		data: {
			simpleData: {
				enable: true,
				pIdKey: 'parent'
			}
		},
		callback: {
			onClick: function(event, treeId, node, clickFlag){
				event.stopPropagation();
				treeObj.expandNode(node);
			}
		}
	};

	var zNodes = <?=json_encode($aSubsections)?>;
	treeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
	
});
</script>