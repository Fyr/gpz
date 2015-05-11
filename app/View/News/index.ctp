<?
	$title = 'Новости';
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => $title)
	)));
?>
<?=$this->element('title', array('title' => $title))?>
<div class="block">
	<div class="news">
<?
	foreach($aArticles as $article) {
		$this->ArticleVars->init($article, $url, $title, $teaser, $src, '200x');
?>
		<div class="newsItem clearfix">
<?
		if ($src) {
?>
			<a href="<?=$url?>"><img class="thumb" src="<?=$src?>" alt="<?=$title?>" /></a>
<?
		}
?>
			<div class="">
				<a href="<?=$url?>" class="title"><?=$title?></a>
				<?=$teaser?>
			</div>
			<?=$this->element('more', compact('url'))?>
		</div>
<?
	}
?>
	</div>
<?
	echo $this->element('paginate');
?>
</div>
