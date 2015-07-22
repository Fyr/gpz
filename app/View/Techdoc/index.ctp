<?=$this->element('title', array('title' => 'TecDoc каталог'))?>
<div class="catalogPage">
	<div class="block mainContentCatalog" style="margin-left: 0">
		<div class="alphabet">
<?
	$aLetters = array();
	foreach($aCatalog as $row) {
		$letter = $row['Brand']['title'][0];
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
		$letter = $row['Brand']['title'][0];
		if ($currLetter !== $letter) {
			$currLetter = $letter;
			echo $this->element('letter_div', array('anchor' => 'techdoc_'.$currLetter, 'title' => $currLetter));
		}
		echo $this->Html->link($row['Brand']['title'], array('controller' => 'Techdoc', 'action' => 'brand', $row['Brand']['id']));
	}
?>
			
		</div>
	</div>
</div>