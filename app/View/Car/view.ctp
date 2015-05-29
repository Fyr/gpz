<?
	App::uses('Translit', 'Article.Vendor');

	$title = $article['CarType']['title'];
	echo $this->element('bread_crumbs', array('aBreadCrumbs' => array(
		array('label' => $title)
	)));
	echo $this->element('title', compact('title'));
	
	$alphabet = 'АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ';
?>

<div class="catalogPage clearfix">
	<div class="block leftSide">
<?
	foreach($aCarSubtypes as $_article) {
		$this->ArticleVars->init($_article, $url, $title, $teaser, $src, '200x');
		$options = ($_article['CarSubtype']['slug'] == $this->request->param('slug')) ? array('class' => 'active') : null;
		echo $this->Html->link($title, $url, $options);
	}
?>
	</div>
	<div class="block mainContentCatalog">
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
		if ($letter != $currLetter) {
			$currLetter = $letter;
?>
				<div class="letter">
					<a name="<?=Translit::convert($currLetter)?>"><?=$currLetter?></a>
				</div>
<?
		}
?>
				<a class="carSubsection" href="javascript:void(0)"><?=$title?></a>
<?
	}
?>
		</div>
	</div>
</div>

<?=$this->ArticleVars->body($article)?>
<script type="text/javascript">
$(document).ready(function(){
	$('.carSubsection').click(function(){
		$('.outerSearch input[type=text]').val($('.catalogPage .leftSide a.active').html() + ' ' + $(this).html());
		$('form.searchBlock').submit();
	});
});
</script>