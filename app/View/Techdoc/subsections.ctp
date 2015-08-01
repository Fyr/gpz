<?
	$this->Html->css('zTreeStyle/zTreeStyle', array('inline' => false));
	$this->Html->script('vendor/jquery/jquery.ztree.core-3.5.min', array('inline' => false));
	
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
<div class="block tableContent clearfix">
	<ul id="treeDemo" class="ztree"></ul>
</div>
<script type="text/javascript">
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
			onClick: function(event, treeId, treeNode, clickFlag){
				console.log(treeNode);
			}
		}
	};

	var zNodes = <?=json_encode($aSubsections)?>;
	$.fn.zTree.init($("#treeDemo"), setting, zNodes);
});
</script>