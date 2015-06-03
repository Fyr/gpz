<div class="span8 offset2">
<?
    $id = $this->request->data('CarSubsection.id');
    $objectType = 'CarSubsection';
    $title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
    
	$objectID = $this->request->data('CarSubsection.cat_id');
	$title = Hash::get($carSubtype, 'CarSubtype.title').': '.$title;
?>
	<?=$this->element('admin_title', compact('title'))?>
<?
    echo $this->PHForm->create('CarSubsection');
    echo $this->Form->hidden('CarSubsection.id');
    echo $this->Form->hidden('Seo.id', array('value' => Hash::get($this->request->data, 'Seo.id')));
    $aTabs = array(
        'General' => $this->element('/AdminContent/admin_edit_'.$objectType),
		'Text' => $this->element('Article.edit_body'),
		'SEO' => $this->element('Seo.edit')
    );
    if ($id) {
        $aTabs['Media'] = $this->element('Media.edit', array('object_type' => $objectType, 'object_id' => $id));
    }
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index', $objectID))));
    echo $this->PHForm->end();
?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	// var $grid = $('#grid_FormField');
});
</script>