<?
	$this->Html->css('jquery.tooltip', array('inline' => false));
	$this->Html->script('vendor/jquery/jquery.tooltip.min', array('inline' => false));
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
<?=$this->element('title', array('title' => $article['Page']['title']))?>
<?=$this->ArticleVars->body($article)?>
<div class="show-mobile"></div>
<script type="text/javascript">
$(document).ready(function(){
	$('.catalog .item').tooltip({
		'dialog_content_selector' : 'ul.tooltip_description',
		'opacity' : 1,
		'animation_distance' : 100,
		'arrow_left_offset' : 90,
        'arrow_right_offset' : 0,
        'arrow_top_offset' : 70,
	});
	
	if ($('.show-mobile:visible').length) {
		$('.catalog .item > a, .catalog .item .name > a').each(function(){
			var url = $(this).attr('href');
			$(this).attr('href', 'javascript:void(0)');
		});
	}
});
</script>