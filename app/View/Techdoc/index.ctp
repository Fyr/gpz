<?
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'TecDoc')
	)));
	echo $this->element('title', array('title' => 'TecDoc каталог'));
?>
<div class="catalogPage">
	<div class="block mainContentCatalog" style="margin-left: 0">
		<div class="alphabet">
<?
	$aLetters = array();
	foreach($aCatalog as $row) {
		$letter = $row['title'][0];
		$aLetters[$letter] = $letter;
	}
	foreach($aLetters as $letter) {
		echo $this->Html->link($letter, '#techdoc_'.$letter);
	}
?>
		</div>
		<div class="content">
<?
	$currLetter = '';
	foreach($aCatalog as $row) {
		$letter = $row['title'][0];
		if ($currLetter !== $letter) {
			$currLetter = $letter;
			echo $this->element('letter_div', array('anchor' => 'techdoc_'.$currLetter, 'title' => $currLetter));
		}
		echo $this->Html->link($row['title'], array('controller' => 'Techdoc', 'action' => 'brand', $row['id']));
	}
?>
			
		</div>
	</div>
</div>