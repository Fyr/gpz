<?
	$this->Html->css('jquery.tooltip', array('inline' => false));
	$this->Html->script('vendor/jquery/jquery.tooltip.min', array('inline' => false));
?>
<div class="catalog block clearfix">
<?
	foreach($aCarTypes as $_article) {
		$this->ArticleVars->init($_article, $url, $title, $teaser, $src, '200x');
		// Zzap + TecDoc - гарантировано есть все ссылки на лого
		// для AutoXP - надо проверять
		// по умолчанию - ссылка на TecDoc
		$urls = array(
			'TecDoc' => array('controller' => 'Techdoc', 'action' => 'index', strtolower($title)),
			'AutoZ' => $url
		);
		if (isset($marks['AutoXP']) && isset($marks['AutoXP'][$title])) {
			$urls['AutoXP'] = array('controller' => 'autoxp', 'action' => 'brand', $marks['AutoXP'][$title]['id']);
		}
?>
	<div class="item tooltiptop">
<?
		if ($src) {
?>
		<a href="javascript:void(0)"><img src="<?=$src?>" alt="<?=$title?>" /></a>
<?
		}
?>
		<div class="name"><?=$this->Html->link($title, 'javascript:void(0)')?></div>
		<ul class="tooltip_description" style="display:none" title="Список каталогов">
<?
		foreach($urls as $title => $url) {
?>
			<li><?=$this->Html->link($title, $url)?></li>
<?
		}
?>
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
        'event_in': 'click'
	});
/*	
	if ($('.show-mobile:visible').length) {
		$('.catalog .item > a, .catalog .item .name > a').each(function(){
			var url = $(this).attr('href');
			$(this).attr('href', 'javascript:void(0)');
		});
	}
	*/
});
</script>