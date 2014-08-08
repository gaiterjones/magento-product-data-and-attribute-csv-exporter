<?php
/**
 *  
 *  Copyright (C) 2014
 *
 *	DATA / ATTRIBUTE / Export for MAGE
 *
 *  @who	   	PAJ
 *  @info   	paj@gaiterjones.com
 *  @license    blog.gaiterjones.com
 *
 */

// APPLICATION CONFIGURATION
// 
// includes	
require_once './php/Magento/Connect/Connect.php';
require_once './php/Helper/Data/Data.php';
require_once './php/Magento/Collection/Collection.php';
require_once './php/Magento/Product/Attributes/Attributes.php';
require_once './php/Magento/Product/Category/Category.php';
require_once './php/Application.php';


// Setup error handling
// error_reporting(0);


// debugging
error_reporting(E_ALL);


// Edit configuration settings here
//
//
class config
{

	// define path to Magento app
	const pathToMagentoApp = '/home/www/dev/magento/app/Mage.php';
	
	// define ean export file path and name
	const pathToExportFile = '/home/www/medazzaland/cache/MAGEExportData.txt';
	
	// define ean code prefix - country / manufacturer
	const eanPrefix = '123456';

	
	public function __construct()
	{

	}
	
	
    public function get($constant) {
	
	    $constant = 'self::'. $constant;
	
	    if(defined($constant)) {
	        return constant($constant);
	    }
	    else {
	        return false;
	    }

	}
}


