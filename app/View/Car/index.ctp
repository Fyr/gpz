<?
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoZ')
	)));
	echo $this->element('title', array('title' => 'Каталог AutoZ'));
?>
<div class="catalog block clearfix">
<?
	foreach($aCarTypes as $_article) {
		$this->ArticleVars->init($_article, $url, $title, $teaser, $src, '200x');
?>
	<div class="item tooltiptop">
<?
		if ($src) {
?>
		<a href="<?=$url?>"><img src="<?=$src?>" alt="<?=$title?>" /></a>
<?
		}
?>
		<div class="name"><a href="<?=$url?>"><?=$title?></a></div>
		<ul class="tooltip_description" style="display:none" title="Список каталогов">
			<li><a href="<?=$this->Html->url(array('controller' => 'TechDoc', 'action' => 'index', strtolower($title)))?>">TecDoc</a></li>
			<li><a href="<?=$url?>">AutoZ</a></li>
		</ul>
	</div>
<?
	}
?>
</div>