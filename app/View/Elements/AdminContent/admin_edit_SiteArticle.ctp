<?
	echo $this->PHForm->input('status', array('label' => false, 'multiple' => 'checkbox', 'options' => array('published' => 'Published', 'featured' => 'Featured'), 'class' => 'checkbox inline'));
	echo $this->element('Article.edit_title');
	echo $this->element('Article.edit_slug');
	echo $this->PHForm->input('subcat_id', array('options' => $aCategoryOptions, 'style' => 'width: auto'));
	echo $this->PHForm->input('teaser');
