<?
	$title = $article[$objectType]['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'Статьи', 'url' => array('controller' => 'Articles', 'action' => 'index')),
		array('label' => 'Просмотр статьи')
	)));
?>
<?=$this->element('title', compact('title'))?>
<div class="block">
	<?=$this->ArticleVars->body($article)?>
</div>