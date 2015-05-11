<?php
App::uses('AppHelper', 'View/Helper');
class ObjectTypeHelper extends AppHelper {
    public $helpers = array('Html');
    
    private function _getTitles() {
        $Titles = array(
            'index' => array(
                'Article' => __('Articles'),
                'Page' => __('Static pages'),
                'News' => __('News'),
                'CategoryNews' => __('News categories'),
                'CategoryArticle' => __('Article categories'),
                'SubcategoryArticle' => __('Article subcategories'),
                'Product' => __('Products'),
                'FormField' => __('Tech.params'),
                'User' => __('Users'),
                'Brand' => __('Brands'),
                'CarType' => __('Car Types'),
                'CarSubtype' => __('Car Subtypes'),
                'CarSubsection' => __('Car Subsections'),
            ), 
            'create' => array(
                'Article' => __('Create Article'),
                'Page' => __('Create Static page'),
                'News' => __('Create News article'),
                'CategoryNews' => __('Create News category'),
                'CategoryArticle' => __('Create Article category'),
                'SubcategoryArticle' => __('Create Article subcategory'),
                'Subcategory' => __('Create Subcategory'),
                'Product' => __('Create Product'),
                'FormField' => __('Create tech.param'),
                'User' => __('Create User'),
                'Brand' => __('Create Brand'),
                'CarType' => __('Create Car Type'),
                'CarSubtype' => __('Create Car Subtype'),
                'CarSubsection' => __('Create Car Subsection'),
            ),
            'edit' => array(
                'Article' => __('Edit Article'),
                'Page' => __('Edit Static page'),
                'News' => __('Edit News article'),
                'CategoryNews' => __('Edit News category'),
                'CategoryArticle' => __('Edit Article category'),
                'SubcategoryArticle' => __('Edit Article subcategory'),
                'Subcategory' => __('Edit Subcategory'),
                'Product' => __('Edit Product'),
                'FormField' => __('Edit tech.param'),
                'User' => __('Edit User'),
                'Brand' => __('Edit Brand'),
                'CarType' => __('Edit Car Type'),
                'CarSubtype' => __('Edit Car Subtype'),
                'CarSubsection' => __('Edit Car Subsection'),
            )
        );
        return $Titles;
    }
    
    public function getTitle($action, $objectType) {
        $aTitles = $this->_getTitles();
        return (isset($aTitles[$action][$objectType])) ? $aTitles[$action][$objectType] : $aTitles[$action]['Article'];
    }
    
    public function getBaseURL($objectType, $objectID = '') {
        return $this->Html->url(array('action' => 'index', $objectType, $objectID));
    }
}