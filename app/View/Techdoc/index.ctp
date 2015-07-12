<?
	// App::uses('Translit', 'Article.Vendor');
/*
	$title = $carSubtype['CarSubtype']['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => $carSubtype['CarType']['title'], 'url' => SiteRouter::url($carType)),
		array('label' => $title)
	)));
	echo $this->element('title', compact('title'));
*/
	$mark_id = $this->request->pass[0];
?>

<div class="catalogPage clearfix">
	<div class="block mainContentCatalog" style="margin-left: 0">
		<div class="content">
<?
	foreach($aModels as $title => $aSubModels) {
		echo $this->element('letter_div', array('anchor' => '', 'title' => $title));
		foreach($aSubModels as $submodel) {
			$_title = substr($submodel['year_from'], 0, 4).'/'.substr($submodel['year_from'], 4);
			if (isset($submodel['year_to'])) {
				$_title.= ' - '.substr($submodel['year_to'], 0, 4).'/'.substr($submodel['year_to'], 4);
			}
			echo $this->Html->link($_title, array('action' => 'model', $mark_id, $submodel['id']));
		}
	}
?>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.carSubsection').click(function(){
		// $('.outerSearch input[type=text]').val($('.catalogPage .leftSide a.active').html() + ' ' + $(this).html());
		// $('form.searchBlock').submit();
		// showLoader();
	});
});
</script>