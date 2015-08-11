<?
	$title = $article['CarType']['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoZ', 'url' => array('action' => 'index')),
		array('label' => $title)
	)));
	echo $this->element('title', compact('title'));
?>
<div class="block clearfix">
	<?=$this->ArticleVars->body($article)?>
</div>

