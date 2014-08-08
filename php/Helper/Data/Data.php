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
 * HELPER CLASS
 * -- Provides helper functions
 * @access public
 * @return nix
 */
class Application_Helper_Data 
{


	function ean13CheckDigit($_digits){
	
		$_digits =(string)$_digits;
		$even_sum = $_digits{1} + $_digits{3} + $_digits{5} + $_digits{7} + $_digits{9} + $_digits{11};
		$even_sum_three = $even_sum * 3;
		$odd_sum = $_digits{0} + $_digits{2} + $_digits{4} + $_digits{6} + $_digits{8} + $_digits{10};
		$total_sum = $even_sum_three + $odd_sum;
		$next_ten = (ceil($total_sum/10))*10;
		$check_digit = $next_ten - $total_sum;
		
		return $_digits . $check_digit;
	
	}
	
	function exportToFile($_header,$_dataArray,$_file,$_seperator)
	{
			ini_set('default_charset','UTF-8');
			
			$_fileHandle = fopen($_file, 'w');
			
				// write header
				fwrite($_fileHandle, implode($_seperator, $_header)."\r\n");
				
				// write data
				foreach ($_dataArray as $_data)
				{
					fwrite($_fileHandle, implode($_seperator, $_data)."\r\n");
				}
			
			fclose($_fileHandle);

		if (!is_writable($_file)) {			
		
			throw new exception ('Target file "'. $_file. '" was not created / is not writeable.');
		}
	
	}
	
	function exportToOutput($_header,$_dataArray,$_seperator)
	{

			// write header
			$_output=implode($_seperator, $_header)."\r\n";
			
			// write data
			foreach ($_dataArray as $_data)
			{
				$_output=$_output.implode($_seperator, $_data)."\r\n";
			}
		
		return $_output;
	
	}	


}  
?>