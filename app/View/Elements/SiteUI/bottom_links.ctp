<ul class="menu clearfix">
<?
	foreach($aBottomLinks as $id => $item) {
		$class = (strtolower($id) == strtolower($currMenu)) ? ' class="active" style="font-weight: bold;"' : '';
?>
				<li<?=$class?>><?=$this->Html->link('<span class="icon smallArrow"></span>'.$item['label'], $item['href'], array('escape' => false))?></li>
<?
	}
?>
			</ul>
</ul>