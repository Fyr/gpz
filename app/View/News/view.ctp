<?
	$title = $article[$objectType]['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'Новости', 'url' => array('controller' => 'News', 'action' => 'index')),
		array('label' => 'Просмотр новости')
	)));
?>
<?=$this->element('title', compact('title'))?>
<div class="block">
	<?=$this->ArticleVars->body($article)?>
</div>