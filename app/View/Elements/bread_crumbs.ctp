<?
	if ($aBreadCrumbs) {
?>
<ul class="breadCrumbs clearfix">
	<li><a href="/">Главная</a></li>
<?
		foreach($aBreadCrumbs as $item) {
			if (isset($item['url'])) {
?>
	<li class="separate">/</li>
	<li><?=$this->Html->link($item['label'], $item['url'])?></li>
		
<?
			} else {
?>
	<li class="separate">/</li>
	<li><span><?=$item['label']?></span></li>
	
<?
			}
		}
?>
</ul> 
<?
	}
?>
