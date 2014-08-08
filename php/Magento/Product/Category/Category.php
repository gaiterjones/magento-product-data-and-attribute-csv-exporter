<?php	
/**
 *  
 *  Copyright (C) 2014
 *
 *
 *  @who	   	PAJ
 *  @info   	paj@gaiterjones.com
 *  @license    blog.gaiterjones.com
 * 	
 *
 */

 /**
 * Magento collection class
 * load a product and get the first category name
 */
class Application_Magento_Product_Category extends Application_Magento_Connect {



	public function __construct() {

		parent::__construct();
		
	}
	
	public function getCategory($_id,$_debug=false)
	{
		$_product = Mage::getModel('catalog/product')->load($_id);
		$_categoryIds = $_product->getCategoryIds();

		if(count($_categoryIds) ){
			$_firstCategoryId = $_categoryIds[0];
			$_category = Mage::getModel('catalog/category')->load($_firstCategoryId);

			return $_category->getName();
		}
		
		return false;
	}
	
}