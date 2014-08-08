<?php
/**
 *  
 *  Copyright (C) 2014
 *
 *	EAN Generation / DATA Export for MAGENTO
 *
 *  @who	   	PAJ
 *  @info   	paj@gaiterjones.com
 *  @license    blog.gaiterjones.com
 * 	
 *	usage - browser http://blah.com/ean.php?export
 			command line php ean.php debug
 *
 * 	VERSION 1.6 24.07.2014 
 */

/* Main application class */
class Application
{
	
	protected $__;
	protected $__config;
	
	public function __construct() {
		
		$this->loadConfig();

		$this->exportMagentoData();
		

	}

	private function exportMagentoData()
	{
		$_debug=false;
		if (php_sapi_name() === 'cli') { // from command line
			
			foreach($_SERVER['argv'] as $_cliVar)
			{
				if ($_cliVar==="debug") { $_debug = true;}
			}
		}
		
		$_count=0;
		$_skippedCount=0;
		$_childCount=0;
		$_productLimit=0;
		
		// export data that has no attribute data ??
		// TRUE = YES, FALSE= NO
		$_exportAllAttributeData=true;
		
		$_data=array();
		
		// -- PRODUCT DATA EXPORT OPTIONS
		//
		$_getProductData=array(
				'name' => true,				
				'description' => false,
				'imageurl' => true,				
				'producturl' => true,	
				'type' => false,				
				'ean' => false,
				'category' => true
		);	
		
		// -- array of attributes we want to export
		//	MAGE NAME => CSV LABEL
		//
		$_getProductAttributes=array(
				'colour1' => 'color'
		);	
		
		// --
		// eXCLUsuiONS
		$_excludedProductIDs=array(); // array of product ids to exclude from export
		$_excludedProductSKUs=array('BULK'); // array of product skus (or part of sku) to exclude from export

		// -- seperator
		$_seperator="\t"; // TAB
		//$_seperator=','; // COMMA
		
		// -- EAN
		//
		$_eanPrefix=$this->get('eanprefix');
		$_eanPrefixLength=strlen($_eanPrefix);
		$_eanProductID=0;
		
		// S T A R T
		
		// LOAD ALL PRODUCTS
		//
		$this->getProductCollectionAllSKUs();
		
		$_products=$this->get('collection');

		foreach($_products as $_id=>$_product)
		{
			// manually exclude products
			if (in_array($_id, $_excludedProductIDs)) {
				if($_debug) {echo $_count. ' - '. $_sku. ' - SKIPPED (ID)'. "\n";}
				$_skippedCount ++;
				continue 2;
			}
			
			$_count++;
			
			$_productData=array();
		
			if ($_productLimit && $_count > $_productLimit) { break; } // limit to x products break if using too much mem
			
			
			$_sku = utf8_decode($_product->getSku());
			
			// manually exclude skus
			foreach ($_excludedProductSKUs as $_k => $_v)
			{
				if (strpos(strtolower($_sku),strtolower($_v)) !== false) {
					if($_debug) {echo $_count. ' - '. $_sku. ' - SKIPPED (SKU)'. "\n";}
					$_skippedCount ++;
					continue 2;
				}
			}
			
			// -- ID not optional
			array_push($_productData,$_id);
			
			// -- SKU - not optional

			array_push($_productData,$_sku);
			
			
			if ($_getProductData['name'])
			{
				// -- product name
				
				$_productName=utf8_decode($_product->getName());
				array_push($_productData,$_productName);
			}			
			
			if ($_getProductData['description'])
			{
				// -- product description
				// -- product description clean up
				
				$_productDescription=$this->getProductDescription($_product->getDescription());
				array_push($_productData,$_productDescription);
			}
			
			if ($_getProductData['imageurl'])
			{
				// -- product image
				$_imageBaseURL=$this->get('baseurlmedia'). 'catalog/product';
				
				// -- product image url
				$_productImage=$_imageBaseURL. $_product->getImage();
				
				array_push($_productData,$_productImage);
			}
			
			if ($_getProductData['producturl'])
			{
				// -- product url
				$_productURL=$_product->getProductUrl();
				
				array_push($_productData,$_productURL);
			}

			$_productType=$_product->getTypeId();			
			
			if ($_getProductData['type'])
			{			
			
				// -- product type
				array_push($_productData,$_productType);
			}
			
			if ($_getProductData['ean'])
			{	
				// -- ean using product id
				$_ean=Application_Helper_Data::ean13CheckDigit($_eanPrefix. str_pad($_id, (12 - $_eanPrefixLength), "0", STR_PAD_LEFT));
				
				// -- ean using generated number
				//$_eanProductID ++; // manual ean id
				//$_ean=Application_Helper_Data::ean13CheckDigit($_eanPrefix. str_pad($_eanProductID, (12 - $_eanPrefixLength), "0", STR_PAD_LEFT));
				
				array_push($_productData,$_ean);
			}
			
			if ($_getProductData['category'])
			{	
				// -- GET CAGEGORY - PARENT
				//
				$_obj=new Application_Magento_Product_Category();
					$_productCategory=utf8_decode($_obj->getCategory($_product->getId()));
				unset($_obj);
				array_push($_productData,$_productCategory);
			}
			
			// -- GET ATTRIBUTES - PARENT
			//
			$_obj=new Application_Magento_Product_Attributes();
				$_productAttributes=$_obj->getAttribute($_product->getId(),$_getProductAttributes);
			unset($_obj);
			
			if ($_debug) {
				if ($_productAttributes) {
				
					if (is_array($_productAttributes)) {
					
						foreach ($_productAttributes as $_name=>$_parentAttribute)
						{
						
							echo $_count. ' - '. $_sku. ' - '. $_name. ' - '. utf8_decode($_parentAttribute). "\n";
						}
					
					} else {
					
						echo $_count. ' - '. $_sku. ' - '. $_name. ' - '. utf8_decode($_parentAttribute). "\n";
					}
				}
			}			
			
			foreach ($_getProductAttributes as $_attributeLabel => $_attributeMageName)
			{
				if (isset($_productAttributes["$_attributeLabel"]))
				{
				
					if ($_attributeMageName==='price') {
						array_push($_productData,($_productAttributes["$_attributeLabel"] ? number_format($_productAttributes["$_attributeLabel"], 2, '.', '') : 'xxx'));
						continue;
					}
					
					if ($_attributeMageName==='stock') {
						array_push($_productData,($_productAttributes["$_attributeLabel"] ? number_format($_productAttributes["$_attributeLabel"]) : 'xxx'));
						continue;
					}
					
					$_attributeText=utf8_decode(trim($_productAttributes["$_attributeLabel"]));
					
					// save to array
					array_push($_productData,(!empty($_attributeText) ? $_attributeText : 'xxx'));
					
				} else {
				
					if ($_exportAllAttributeData) {
						//array_push($_productData,('No data for '. $_attributeLabel));
						array_push($_productData,('xxx'));
					}
				}
			}			

			$_data[]=$_productData; // commit parent product data to array
	
		
			if ($_productType == 'configurable' || $_productType == 'grouped')
			{
				// -- get child product collection of configurable / grouped products
				//
				//
				$_childCollection=$this->getProductCollectionChildren($_id,$_productType);
				
			
				// -- export child data
				//
				foreach ($_childCollection as $_childID => $_childProduct) {
					
					// -- GET ATTRIBUTES - child
					//
					
					$_productData=array();
					
					$_childCount ++;
					
	
					// -- child product variables
					//
					
					// -- ID
					array_push($_productData,$_childProduct->getId());
					
					// -- SKU
					$_childSku = $_childProduct->getSku();
					array_push($_productData,$_childSku);
					
					if ($_getProductData['name'])
					{
						// -- product name
						
						$_productName=utf8_decode($_childProduct->getName());
						array_push($_productData,$_productName);
					}

					if ($_getProductData['description'])
					{
						// -- product description FROM PARENT
						// -- product description clean up
						
						array_push($_productData,$_productDescription);
					}	

					if ($_getProductData['imageurl'])
					{
						// -- product image FROM PARENT
						
						array_push($_productData,$_productImage);
					}
					
					if ($_getProductData['producturl'])
					{
						// -- product url FROM PARENT
						$_productURL=$_product->getProductUrl();
						
						array_push($_productData,$_productURL);
					}

					if ($_getProductData['type'])
					{			
								
						$_productType='child-'. $_childProduct->getTypeId();
						array_push($_productData,$_productType);
					}					
					
					if ($_getProductData['ean'])
					{						
						$_productChildEan=$_childProduct->getExt_ean();
						
						// ean using product id
						$_ean=Application_Helper_Data::ean13CheckDigit($_eanPrefix. str_pad($_childProduct->getId(), (12 - $_eanPrefixLength), "0", STR_PAD_LEFT));					
						array_push($_productData,$_ean);
					}
					
					if ($_getProductData['category'])
					{	
						// -- GET CAGEGORY - FROM PARENT
						//
						array_push($_productData,$_productCategory);
					}					
					
					// check stock status
					$_childProductInStock=true;
					$_childProductStock=$_childProduct->getStockItem();
					
						if(!$_childProductStock->getIsInStock()){
							$_childProductInStock=false;
						}

					// -- ATTRIBUTES
					//
					
					// -- load children (again) individually to get FRONTEND - defined attributes values (collection returns index only).
					//
					$_obj=new Application_Magento_Product_Attributes();
						$_childAttributes=$_obj->getAttribute($_childProduct->getId(),$_getProductAttributes);
					unset($_obj);
					
					if ($_debug) {
						if ($_childAttributes) {
						
							if (is_array($_childAttributes)) {
							
								foreach ($_childAttributes as $_name=>$_childAttribute)
								{
								
									echo $_count. ' ['. $_childCount. '] - '. $_childSku. ' - '. $_name. ' - '. utf8_decode($_childAttribute). "\n";
								}
							
							} else {
							
								echo $_count. ' ['. $_childCount. '] - '. $_childSku. ' - '. $_name. ' - '. utf8_decode($_childAttribute). "\n";
							}
						}
					}
					
				
					foreach ($_getProductAttributes as $_attributeLabel => $_attributeMageName)
					{
						if (isset($_childAttributes["$_attributeLabel"]))
						{
						
							if ($_attributeMageName==='price') {
								array_push($_productData,($_childAttributes["$_attributeLabel"] ? number_format($_childAttributes["$_attributeLabel"], 2, '.', '') : 'xxx'));
								continue;
							}
							
							if ($_attributeMageName==='stock') {
								array_push($_productData,($_childAttributes["$_attributeLabel"] ? number_format($_childAttributes["$_attributeLabel"]) : 'xxx'));
								continue;
							}

							$_attributeText=utf8_decode(trim($_childAttributes["$_attributeLabel"]));
							
							// save to array
							array_push($_productData,(!empty($_attributeText) ? $_attributeText : 'xxx'));							

							
						} else {
						
							if ($_exportAllAttributeData) {
								//array_push($_productData,('no data for '. $_attributeLabel));
								array_push($_productData,('xxx'));
							}
						}
					}						
					
				
					$_data[]=$_productData; // commit child product data to array


				}
			
			} // configurable product
		
		}
		
		// delimited header
		//
		$_header = array();
		
		array_push($_header,'id');
		array_push($_header,'sku');
		
		foreach ($_getProductData as $_k => $_v)
		{
			if ($_v)
			{	
				array_push($_header,$_k);
			}
		}
		
		foreach ($_getProductAttributes as $_k => $_v)
		{
			array_push($_header,$_k);
		}		
		
		// export data
		if (php_sapi_name() === 'cli') { // from command line
			
			$_file=$this->__config->get('pathToExportFile');
			$_cr="\n";
		
			Application_Helper_Data::exportToFile($_header,$_data,$_file,$_seperator);
			
			if ($_debug) { // with command line debug switch
				echo '< MAGENTO EXPORT -> D E B U G >' . $_cr;
				echo 'Exporting data for '. ($_count + $_childCount). ' Magento product/s ... '. $_cr; 
				echo $_skippedCount. ' products excluded.'. $_cr;
				echo 'Data file created at ' . $_file. $_cr;
				echo 'Export finished.' . $_cr;
			}
			
		} else if(isset($_GET['export'])){ // from web browser
		
			header("Content-type:text/octect-stream");
			header("Content-Disposition:attachment;filename=exportMyData.txt");
			
			// dump data as binary attachment
			echo Application_Helper_Data::exportToOutput($_header,$_data,$_seperator);

		}
		
	}


	private function getProductCollectionChildren($_id,$_type)
	{
		$_storeID=0;
		$_obj=new Application_Magento_Collection();
		
		$_obj->getChildProducts($_id,$_storeID,$_type);
			$_childCollection=$_obj->get('collection');
				unset ($_obj);		
		
		return $_childCollection;
	}	
	
	private function getProductCollectionAllSKUs($_storeID=0)
	{
		$_obj=new Application_Magento_Collection();
		
		$_obj->getAllSKUs($_storeID);
		
		$this->set('collection',$_obj->get('collection'));
		$this->set('baseurlmedia',$_obj->get('baseurlmedia'));

		unset ($_obj);
		
	}
	
	private function getProductCollectionByAttribute($_storeID=0)
	{
		$_obj=new Application_Magento_Collection();
		
		$_obj->getProductByAttribute($_storeID,'product_primary_colour','green');

		$this->set('collection',$_obj->get('collection'));
		$this->set('baseurlmedia',$_obj->get('baseurlmedia'));

		unset ($_obj);
		
	}	
	
	private function getProductDescription($_text)
	{
		$_productDescription=strip_tags($_text);
		$_productDescription=trim($_productDescription);
		$_productDescription=ltrim($_productDescription,'=');;
		$_productDescription=preg_replace("/\s+/", " ", $_productDescription);
		$_productDescription=utf8_decode($_productDescription);	
		
		return $_productDescription;
	}
	
	private function loadConfig()
	{
		set_time_limit(0);
		ini_set('memory_limit', '256M');
		
		$this->__config= new config();
		$this->set('eanprefix',$this->__config->get('eanPrefix'));
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