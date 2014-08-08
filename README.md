## Magento Product Data and Attribute Exporter


### Synopsis
A customiseable PHP application to export Magento product data and product attributes

### Version
***
	@version		1.6.0
	@since			08 2013
	@author			gaiterjones
	@documentation	blog.gaiterjones.com
	@twitter		twitter.com/gaiterjones
	
### Installation

Copy the files to an appropriate folder on the same system as your Magento installation.

### Configuration

Edit the filea

	config/applicationConfig.php
	php/Application.php
	

Add the path to your Magento installation Mage.pgp file to the constant pathToMagentoApp

Add the path to the export file. Make sure the folder is writeable for your current user or web access group.

For EAN barcodes add your EAN prefix.

In php/Application edit the arrays that contain your product data and attribute options

$_getProductData - set to true or false to export the specified product data.

$_getProductAttributes - set the array to contain the attributes you want to export i.e.

	'colour1' => 'color'
	
Where colour1 will be the name of the exported column and color is the name of the Magento attribute.

Run the application by browsing to it with a web browser or from the comman line using

	php export.php debug
	
The debug switch enables progress output from the script, if omitted the script will run silently.

For a large product database the script may take a few minutes to run, the exported data will be written to the file specified when finished.

To exclude product ID's or SKUs from the export add them to the exclusion arrays.

	


## License

The MIT License (MIT)
Copyright (c) 2013 Peter Jones

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.