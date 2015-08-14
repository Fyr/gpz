<?
	$this->Html->css(array('/Table/css/grid', 'jquery.fancybox'), array('inline' => false));
	$this->Html->script(array('vendor/jquery/jquery.fancybox.pack'), array('inline' => false));
	
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'TecDoc', 'url' => $this->Html->url(array('action' => 'index'))),
		array('label' => $mark['title'], 'url' => array('controller' => 'Techdoc', 'action' => 'brand', $mark['id'])),
		array('label' => $model['title'], 'url' => array('action' => 'model', $mark['id'], $model['id'])),
		array('label' => $submodel['type'], 'url' => array('action' => 'subsections', $mark['id'], $model['id'], $submodel['id'])),
		array('label' => $subsection['name'])
	)));
	$title = $subsection['name'].' для '.$mark['title'].' '.$model['title'].' '.$submodel['type'].' '.$model['date_issue'];
	echo $this->element('title', compact('title'));
?>
<div class="block tableContent clearfix">
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Лого</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Производитель</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Номер</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Изображение</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Наименование</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Ссылка</a>
			</th>
		</tr>
		</thead>
		<tbody>
<? 
		foreach ($aAutoparts as $row) {
			$descr = array();
			foreach($row['criteria'] as $item) {
				$descr[] = $item['key'].': '.$item['value'];
			}
			
			$params = array('brand' => $row['brand'], 'number' => $row['article']);
?>
			<tr class="grid-row">
				<td align="center">
					<?=($row['logo']) ? $this->Html->image($row['logo'], array('class' => 'brand-logo')) : ''?>
				</td>
				<td>
					<?=$row['brand']?>
				</td>
				<td nowrap="nowrap"><?=$row['article']?></td>
				<td align="center">
<?
			if ($row['image']) {
?>
					<a class="fancybox" href="<?=$row['image']?>" rel="photos">
						<?=$this->Html->image($row['image'], array('class' => 'product-img'))?>
					</a>
<?
			}
?>
				</td>
				<td>
					<?=$row['name']?>
				</td>
				<td nowrap="nowrap" align="center">
					<?=$this->Html->link('Цены и замены', array('controller' => 'Search', 'action' => 'price', '?' => $params))?>
				</td>
			</tr>
<? 
		}
?>
		</tbody>
		</table>
</div>
<script type="text/javascript">
$(function(){
	$('.fancybox').fancybox({
		padding: 5
	});
});
</script>