<?
	$this->Html->css(array('/Table/css/grid'), array('inline' => false));
?>
<?=$this->element('admin_title', array('title' => __('Incoming IP stats')))?>
<div class="span8 offset2">
<?=$this->element('admin_content')?>
	<table class="grid table-bordered shadow" width="95%">
	<thead>
		<tr class="first table-gradient">
			<th class="nowrap">
				<a class="grid-unsortable" href="javascript:void(0)">IP</a>
			</th>
			<th class="nowrap">
				<a class="grid-unsortable" href="javascript:void(0)"><?=__('Amount')?></a>
			</th>
			<th class="nowrap">
				<a class="grid-unsortable" href="javascript:void(0)"><?=__('Server name')?></a>
			</th>
		</tr>
	</thead>
	<tbody>
<?
	$class = 'even';
	foreach($rows as $row) {
		$class = ($class == 'even') ? 'odd' : 'even';
?>
		<tr class="grid-row">
			<td class="<?=$class?>"><?=$row['IpLog']['ip']?></td>
			<td class="<?=$class?> text-right"><?=$row[0]['count']?></td>
			<td class="<?=$class?>"><?=gethostbyaddr($row['IpLog']['ip'])?></td>
		</tr>

<?
	}
?>
		<tr class="grid-footer table-gradient" id="last-tr">
			<td class="nowrap" colspan="4">
				<table>
				<tbody>
					<tr><td class="grid-checked-actions"><div class="hide"><small></small><div class="btn-group"><a href="#" data-toggle="dropdown" class="btn dropdown-toggle btn-mini"><span class="caret"></span></a><ul class="dropdown-menu"><li><a onclick="undefined" href="javascript:void(0)" class=""><i class="icon-color icon-delete"></i>Удалить помеченные записи</a></li></ul></div></div></td><td class="text-center grid-paging"></td><td class="text-right grid-records-count"></td></tr>
				</tbody>
				</table>
			</td>
		</tr>
	</tbody>
	</table>


<?=$this->element('admin_content_end')?>
<?
	echo $this->Html->link(
		'<i class="icon-chevron-left"></i> Назад', 
		array('plugin' => '', 'controller' => 'AdminUtils', 'action' => 'index'), 
		array('class' => 'btn', 'escape' => false))
?>
<br/>
</div>