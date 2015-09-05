<?
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoXP')
	)));
	echo $this->element('title', array('title' => 'AutoXP каталог'));
	echo $this->element('search_vin');
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
		echo $this->Html->link($letter, '#letter_'.$letter);
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
			echo $this->element('letter_div', array('anchor' => 'letter_'.$currLetter, 'title' => $currLetter));
		}
		echo $this->Html->link($row['title'], array('controller' => 'Autoxp', 'action' => 'brand', $row['id']));
	}
?>
			
		</div>
	</div>
</div>