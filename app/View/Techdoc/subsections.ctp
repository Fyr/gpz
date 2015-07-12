<?
	$this->Html->css('zTreeStyle/zTreeStyle', array('inline' => false));
	$this->Html->script('vendor/jquery/jquery.ztree.core-3.5.min', array('inline' => false));
/*
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