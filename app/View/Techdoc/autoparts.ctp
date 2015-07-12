<?
	$this->Html->css('/Table/css/grid', array('inline' => false));
	/*
		$title = $article['CarSubsection']['title'];
		$carType = array('CarType' => $article['CarType']);
		$carSubtype = array('CarSubtype' => $article['CarSubtype'], 'CarType' => $article['CarType']);
		echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
			array('label' => $article['CarType']['title'], 'url' => SiteRouter::url($carType)),
			array('label' => $article['CarSubtype']['title'], 'url' => SiteRouter::url($carSubtype)),
			array('label' => $title)
		)));
	echo $this->element('title', compact('title'));
	*/
?>
<div class="block tableContent clearfix">
		<table align="left" width="100%" class="grid table-bordered shadow" border="0" cellpadding="0" cellspacing="0">
		<thead>
		<tr class="first table-gradient">
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Производитель</a>
			</th>
			<th>
				<a class="grid-unsortable" href="javascript:void(0)">Номер</a>
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
			
			$params = array('classman' => $row['brand'], 'number' => $row['article']);
?>
			<tr class="grid-row">
				<td>
					<?=($row['logo']) ? $this->Html->image($row['logo'], array('class' => 'brand-logo')) : ''?>
					<?=$row['brand']?>
				</td>
				<td nowrap="nowrap"><?=$row['article']?></td>
				<td>
					<?=($row['image']) ? $this->Html->image($row['image'], array('class' => 'product-img')) : ''?>
					<?=$row['name']?><br />
					<?=implode(' / ', $descr)?>
				</td>
				<td nowrap="nowrap">
					<?=$this->Html->link('Цены и замены', array('controller' => 'Search', 'action' => 'price', '?' => $params))?>
				</td>
			</tr>
<? 
		}
?>
		</tbody>
		</table>
</div>
