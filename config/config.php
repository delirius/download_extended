<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 */

/**
 * Content Elements
 */
$GLOBALS['BE_MOD']['system']['tl_download_settings'] = array
    (
    'tables' => array('tl_download_settings'),
    'icon' => 'system/modules/download_extended/assets/icon.png',
);


$GLOBALS['TL_CTE']['files']['download_extended'] = 'ContentDownloadExtended';

/**
 * FRONT END MODULES
 */
$GLOBALS['BE_MOD']['content']['downloadData'] = array
    (
    'tables' => array('tl_download_archive', 'tl_download_data'),
    'icon' => 'system/modules/download_extended/assets/iconlist.png'
);

$GLOBALS['FE_MOD']['miscellaneous']['download_list'] = 'downloadList';
