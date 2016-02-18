<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Elements
	'ContentDownloadExtended' => 'system/modules/download_extended/elements/ContentDownloadExtended.php',
    	'downloadList' => 'system/modules/download_extended/modules/downloadList.php',

));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_download_extended'       => 'system/modules/download_extended/templates',
	'ce_download_extended_debug' => 'system/modules/download_extended/templates',
	'download_list_default' => 'system/modules/download_extended/templates',
    
));
