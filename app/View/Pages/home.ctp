<?=$this->element('title', array('title' => 'TechDoc каталог'))?>
<div class="catalogPage">
	<div class="block mainContentCatalog" style="margin-left: 0">
		<div class="alphabet">
<?
	$aLetters = array();
	foreach($aCatalog['TechDoc']['brands'] as $row) {
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
	foreach($aCatalog['TechDoc']['brands'] as $row) {
		$letter = $row['Brand']['title'][0];
		if ($currLetter !== $letter) {
			$currLetter = $letter;
			echo $this->element('letter_div', array('anchor' => 'techdoc_'.$currLetter, 'title' => $currLetter));
		}
		echo $this->Html->link($row['Brand']['title'], array('controller' => 'Techdoc', $row['Brand']['id']));
	}
?>
			
		</div>
	</div>
</div>
<div class="catalog block clearfix">
<?
	foreach($aCarTypes as $_article) {
		$this->ArticleVars->init($_article, $url, $title, $teaser, $src, '200x');
?>
	<div class="item">
<?
		if ($src) {
?>
		<a href="<?=$url?>"><img src="<?=$src?>" alt="<?=$title?>" /></a>
<?
		}
?>
		<div class="name"><a href="<?=$url?>"><?=$title?></a></div>
	</div>
<?
	}
?>
</div>
<?=$this->element('title', array('title' => $article['Page']['title']))?>
<?=$this->ArticleVars->body($article)?>