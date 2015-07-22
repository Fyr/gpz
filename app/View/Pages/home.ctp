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