<?php
/**
 *  
 *  Copyright (C) 2013 paj@gaiterjones.com
 *	
 *	Helper for Magento
 *
 *  @category   PAJ
 *  @package    
 *  @license    PAJ
 * 	
 *
 */
 
class Application_Magento_Helper_Data {

		// manually change parent product
		// of selected skus
		//
		function sku($_sku)
		{	
			
			// manually load specific SKUs

			if (preg_match('/^GFC-XS-WM/', $_sku)) {			
				return '2813';
			}
			
			if (preg_match('/^UVPOP/', $_sku)) {			
				return '4530';
			}
			
			if (preg_match('/^UVPO/', $_sku)) {			
				return '3471';
			}
			
			if (preg_match('/^UVPD/', $_sku)) {			
				return '3962';			
			}
			
			if (preg_match('/^UVPM/', $_sku)) {			
				return '3869';			
			}

			if (preg_match('/^UVPE/', $_sku)) {			
				return '3579';			
			}	

			if (preg_match('/^UVPG/', $_sku)) {			
				return '3514';			
			}

			if (preg_match('/^GFV-/', $_sku)) {			
				return '2840';			
			}				

			if (preg_match('/^NAP-0*([1-9]|1[0-3])$/', $_sku)) {
				return '2318';
			}
			
			if (preg_match('/^NAP-0*2[0-9]$/', $_sku)) {
				return '2333';			
			}

			if (preg_match('/^GFNU-XS-0*[1-5]$/', $_sku)) {
				return '3340';
			}
			
			if (preg_match('/^GFCP-XS-0*[1-5]$/', $_sku)) {
				return '2834';
			}			

			if (preg_match('/^GFNU-15-0*[1-5]$/', $_sku)) {
				return '3334';
			}
	
			if (preg_match('/^GFCN-0*[1-5]$/', $_sku)) {
				return '2835';
			}
			
			if (preg_match('/^GFRX-0*([1-9]|1[0-9])$/', $_sku)) {
				return '2827';
			}

			if (preg_match('/^MNAS-01/', $_sku)) {			
				return '3521';			
			}
			
			if (preg_match('/^NLOEM/', $_sku)) {			
				return '3508';			
			}			

			if (preg_match('/^MNAS-02/', $_sku)) {			
				return '3521';			
			}

			if (preg_match('/^GFG-0*[1-9]$/', $_sku)) {
				return '2837';
			}
			
			if (preg_match('/^GFC-15-0*([1-9]|[1-7][0-9]|8[0-8]|9[1-9])$/', $_sku)) {
				return '365';
			}	

			if (preg_match('/^GFC-XS-0*([1-9]|[1-7][0-9]|8[0-8]|9[1-9])$/', $_sku)) {
				return '2833';
			}		

			if (preg_match('/^GFFG-XS-0*[1-4]$/', $_sku)) {
				return '2829';
			}			
		
			if (preg_match('/^GFDT-XS-0*[1-7]$/', $_sku)) {
				return '2832';
			}			
			return false;
			
		}
		
}