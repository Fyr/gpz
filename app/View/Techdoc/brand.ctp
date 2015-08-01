<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	
	$title = $mark['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'TecDoc', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $title)
	)));
	
	echo $this->element('title', compact('title'));

	$mark_id = $this->request->pass[0];
?>

<!--div class="catalogPage clearfix">
	<div class="block mainContentCatalog" style="margin-left: 0">
		<div class="content">
<?
	foreach($aModels as $title => $aSubModels) {
		echo $this->element('letter_div', array('anchor' => '', 'title' => $title));
		foreach($aSubModels as $submodel) {
			$_title = $submodel['year_from'];
			if ($submodel['year_to']) {
				$_title.= ' - '.$submodel['year_to'];
			}
			echo $this->Html->link($_title, array('action' => 'model', $mark_id, $submodel['id']));
		}
	}
?>
		</div>
	</div>
</div-->
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
		</tr>
		</thead>
		<tbody>
<?
// делаем группировку по модели	
$models = array();
foreach($aModels as $row) {
	$models[$row['title']][] = $row;
}
// делаем группировку по первому слову модели
$_aModels = array();
foreach($models as $title => $aSubModels) {
	list($mod) = explode(' ', $title);
	$_aModels[$mod.'&nbsp;'][$title] = $aSubModels;
}
foreach($_aModels as $mod => $aModels) {
	
?>
			<tr class="grid-row">
				<td colspan="2" class="subheader">
					<?=$mod?>
				</td>
			</tr>

<?

	foreach($aModels as $title => $aSubModels) {
		foreach($aSubModels as $submodel) {
?>
			<tr class="grid-row">
				<td>
					<?=$this->Html->link($title, array('action' => 'model', $mark_id, $submodel['id']));?>
				</td>
				<td nowrap="nowrap" align="center"><?=$this->Html->link($submodel['date_issue'], array('action' => 'model', $mark_id, $submodel['id']))?></td>
			</tr>
<?
		}
	}
}
?>
		</tbody>
		</table>
	</div>