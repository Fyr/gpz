<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	
	$title = 'Поиск по VIN';
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoXP', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $title)
	)));
	
	echo $this->element('title', compact('title'));
	echo $this->element('search_vin');
?>
<div class="block tableContent clearfix">
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Модель</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Год выпуска</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<?
		foreach ($aSearch as $row) {
?>
			<tr class="grid-row">
				<td><?=$row['title']?></td>
				<td align="center"><?=$row['year_issue']?></td>
				<td nowrap="nowrap" align="center">
					<?=$this->Html->link('подробнее', array('action' => 'searchSections', $row['mark'], urlencode(str_replace('/', '|', $row['hash']))))?>
				</td>
			</tr>
<? 
		}
?>
		</tbody>
		</table>
</div>
