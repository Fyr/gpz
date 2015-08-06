<div class="span8 offset2">
<?
    $id = $this->request->data('Subsection.id');
    $objectType = 'Subsection';
    $title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
    echo $this->element('admin_title', compact('title'));
    
    echo $this->PHForm->create('Subsection');
    echo $this->PHForm->hidden('Subsection.id');
    $aTabs = array(
        'General' => $this->PHForm->input('title'),
    );
    if ($id) {
        $aTabs['Media'] = $this->element('Media.edit', array('object_type' => $objectType, 'object_id' => $id));
    }
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index'))));
    echo $this->PHForm->end();
?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	// var $grid = $('#grid_FormField');
});
</script>