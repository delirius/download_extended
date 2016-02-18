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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['download_extended'] = '{type_legend},type,headline;{source_legend},singleSRC;{dwnconfig_legend},linkTitle,titleText,description;{image_legend},previewImage,previewSettings;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_content']['fields']['previewImage'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['previewImage'],
    'exclude' => true,
    'inputType' => 'fileTree',
    'eval' => array('fieldType' => 'radio', 'files' => true, 'filesOnly' => true, 'extensions' => 'jpg,jpeg,gif,png', 'tl_class' => 'clr'),
    'sql' => "binary(16) NULL",
);

$GLOBALS['TL_DCA']['tl_content']['fields']['previewSettings'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['previewSettings'],
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => array('tl_content_download', 'getDownloadSettings'),
    'eval' => array('chosen' => true, 'mandatory' => true),
    'sql' => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['previewAddToSitemap'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['previewAddToSitemap'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => ' m12'),
    'sql' => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['description'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_content']['description'],
    'exclude' => true,
    'inputType' => 'textarea',
    'eval' => array('style' => 'height:48px', 'tl_class' => 'clr', 'rte' => 'tinyMCE'),
    'sql' => "text NULL"
);

class tl_content_download extends Backend
{

    public function getDownloadSettings()
    {
        $arrDownloadSettings = array();

        $objData = Database::getInstance()->prepare("SELECT id,title FROM tl_download_settings WHERE 1 ORDER BY title")
                ->execute();

        if ($objData->numRows)
        {
            while ($objData->next())
            {
                $arrDownloadSettings[$objData->id] = $objData->title;
            } // while
        }
        return $arrDownloadSettings;
    }

    
}
