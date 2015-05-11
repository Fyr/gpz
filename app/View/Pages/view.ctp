<?
	$aBreadCrumbs = array(
		array('label' => $article['Page']['title'])
	);
?>
<?=$this->element('bread_crumbs', compact('aBreadCrumbs'))?>
<?=$this->element('title', array('title' => $article['Page']['title']))?>
<div class="block clearfix">
	<?=$this->ArticleVars->body($article)?>
</div>
