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
 * Main MAGENTO class
 * -- Connects to MAGENTO
 * @access public
 * @return nix
 */
class Application_Magento_Connect
{

	protected $__config;
	protected $__;
	
	public function __construct() {
		
			$this->loadConfig();
			$this->loadMagento();

	}
	
	
	// -- get app config
	private function loadConfig()
	{
		$this->__config= new config();
	}



	// -- connect to Magento
	private function loadMagento()
	{
		if (file_exists($this->__config->get('pathToMagentoApp'))) {
			
			include_once($this->__config->get('pathToMagentoApp'));
			
		} else {

			throw new exception('Mage NOT Found at '. $this->__config->get('pathToMagentoApp'));
			
		}
		
		umask(0);
		Mage::app();
		Mage::app()->loadArea(Mage_Core_Model_App_Area::AREA_FRONTEND);
		
		$baseUrlMedia = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		
		$this->set('baseurlmedia',$baseUrlMedia);		
	}
	
	
	public function set($key,$value)
	{
		$this->__[$key] = $value;
	}

	public function get($variable)
	{
		return $this->__[$variable];
	}

}  
?>