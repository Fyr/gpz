<?
	$title = $this->ObjectType->getTitle('index', 'CarSubsection');
	$title = Hash::get($carSubtype, 'CarSubtype.title').': '.$title;
	
    $createURL = $this->Html->url(array('action' => 'edit', 0, $objectID));
    $createTitle = $this->ObjectType->getTitle('create', 'CarSubsection');
    
    $actions = $this->PHTableGrid->getDefaultActions('CarSubsection');
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id', $objectID));

?>
<?=$this->element('admin_title', compact('title'))?>
<div class="text-center">
    <a class="btn btn-primary" href="<?=$createURL?>">
        <i class="icon-white icon-plus"></i> <?=$createTitle?>
    </a>
</div>
<br/>
<?
    echo $this->PHTableGrid->render('CarSubsection', array(
        'baseURL' => $this->ObjectType->getBaseURL('CarSubsection', $objectID),
        'actions' => $actions
    ));
?>