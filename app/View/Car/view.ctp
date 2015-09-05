<?
	App::uses('Translit', 'Article.Vendor');
	$this->Html->script('mobile-panel', array('inline' => false));

	$title = $carSubtype['CarSubtype']['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => 'AutoZ', 'url' => array('action' => 'index')),
		array('label' => $carSubtype['CarType']['title'], 'url' => SiteRouter::url($carType)),
		array('label' => $title)
	)));
	echo $this->element('title', compact('title'));
	
	$alphabet = 'АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ';
?>

<div class="catalogPage clearfix">
	<div class="block leftSide list">
<?
	foreach($aCarSubtypes as $_article) {
		$this->ArticleVars->init($_article, $url, $title, $teaser, $src, '200x');
		$options = ($_article['CarSubtype']['slug'] == $this->request->param('carSubtype')) ? array('class' => 'active') : null;
		echo $this->Html->link($title, $url, $options);
	}
?>
	</div>
	<div class="block mainContentCatalog">
<?
	if ($aCarSubsections) {
?>
		<div class="alphabet">
<?
		for($i = 0; $i < mb_strlen($alphabet); $i++) {
			$letter = mb_substr($alphabet, $i, 1);
?>
			<a href="#<?=Translit::convert($letter)?>"><?=$letter?></a>
<?		
		}
?>
		</div>
		<div class="content">
<?
		$currLetter = '';
		foreach($aCarSubsections as $_article) {
			$title = $_article['CarSubsection']['title'];
			$letter = mb_substr($title, 0, 1);
			if ($letter !== $currLetter) {
				$currLetter = $letter;
				echo $this->element('letter_div', array('anchor' => Translit::convert($currLetter), 'title' => $currLetter));
			}
?>
				<a class="carSubsection showLoader" href="<?=SiteRouter::url(Hash::merge($_article, $carSubtype))?>"><?=$title?></a>
<?
		}
?>
		</div>
<?
	} else {
		echo 'По данной модели нет информации об отсеках';
	}
?>
	</div>
</div>

<?=$this->ArticleVars->body($article)?>
<script type="text/javascript">
$(document).ready(function(){
	$('.carSubsection').click(function(){
		// $('.outerSearch input[type=text]').val($('.catalogPage .leftSide a.active').html() + ' ' + $(this).html());
		// $('form.searchBlock').submit();
		// showLoader();
	});
	
});
</script>