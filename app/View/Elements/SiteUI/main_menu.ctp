<ul class="menu clearfix menuDesktop">
<?
	foreach($aNavBar as $id => $item) {
		$class = (strtolower($id) == strtolower($currMenu)) ? ' class="active"' : '';
?>
				<li<?=$class?>><?=$this->Html->link($item['label'], $item['href'])?></li>
<?
	}
?>
</ul>
<ul class="menu menuMobile clearfix">
	<li>
		<a href="javascript: void(0)"><span>Меню</span></a>
		<ul style="display: none">
<?
	foreach($aNavBar as $id => $item) {
		$class = (strtolower($id) == strtolower($currMenu)) ? ' class="active"' : '';
?>
				<li<?=$class?>><?=$this->Html->link($item['label'], $item['href'])?></li>
<?
	}
?>

		</ul>
	</li>
</ul>