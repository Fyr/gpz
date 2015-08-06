<?
	$title = $this->ObjectType->getTitle('index', 'Subsection');
	
    $createURL = $this->Html->url(array('action' => 'edit', 0));
    $createTitle = $this->ObjectType->getTitle('create', 'Subsection');
    
    $actions = $this->PHTableGrid->getDefaultActions('Subsection');
    unset($actions['table']['add']);
    // $actions['table']['add']['href'] = $createURL;
    // $actions['table']['add']['label'] = $createTitle;
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));
    unset($actions['row']['delete']);
?>
<?=$this->element('admin_title', compact('title'))?>
<div class="text-center">
    <!--a class="btn btn-primary" href="<?=$createURL?>">
        <i class="icon-white icon-plus"></i> <?=$createTitle?>
    </a-->
</div>
<br/>
<?
    echo $this->PHTableGrid->render('Subsection', array(
        'baseURL' => $this->ObjectType->getBaseURL('Subsection'),
        'actions' => $actions
    ));
?>