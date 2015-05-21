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
 */
/**
 * Table tl_download_settings
 */
$GLOBALS['TL_DCA']['tl_download_settings'] = array
    (
    // Config
    'config' => array
        (
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'onsubmit_callback' => array
            (
            array('download_extended_c', 'previewDelete')
        ),
        'sql' => array
            (
            'keys' => array
                (
                'id' => 'primary'
            )
        )
    ),
    // List
    'list' => array
        (
        'sorting' => array
            (
            'mode' => 0,
            'fields' => array('title'),
            'flag' => 11,
            'panelLayout' => 'limit'
        ),
        'label' => array
            (
            'fields' => array('title', 'previewImageSize', 'previewImageMargin', 'previewConsiderOrientation'),
            // 'showColumns' => true,
            'label_callback' => array('download_extended_c', 'getRowLabel')
        ),
        'global_operations' => array
            (
            'all' => array
                (
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
            (
            'edit' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'copy' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif'
            ),
            'delete' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            )
        )
    ),
    // Palettes
    'palettes' => array
        (
        '__selector__' => array(''),
        'default' => '{download_extended_legend},title,previewTemplate;{download_extended_image_legend},headline,previewStandardImage,previewImageSize,previewImageMargin,previewConsiderOrientation,previewImageFloating;{download_extended_imageg_legend},previewGenerateImage,pathImageMagick;{download_extended_text_legend},previewIcon,previewExtension,previewFilesizeD,previewFilesizeB;',
    ),
    // Subpalettes
    'subpalettes' => array
    (
    ),
    // Fields
    'fields' => array
        (
        'id' => array
            (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['title'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => ''),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'previewImageSize' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewImageSize'],
            'exclude' => true,
            'inputType' => 'imageSize',
            'options' => $GLOBALS['TL_CROP'],
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => array('rgxp' => 'digit', 'nospace' => true, 'helpwizard' => true, 'tl_class' => 'clr w50'),
            'sql' => "varchar(64) NOT NULL default ''"
        ),
        'previewImageMargin' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewImageMargin'],
            'exclude' => true,
            'inputType' => 'trbl',
            'options' => $GLOBALS['TL_CSS_UNITS'],
            'eval' => array('includeBlankOption' => true, 'tl_class' => 'w50'),
            'sql' => "varchar(128) NOT NULL default ''"
        ),
        'previewGenerateImage' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewGenerateImage'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => ' m12'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'pathImageMagick' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['pathImageMagick'],
            'default' => '/usr/bin',
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'previewStandardImage' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewStandardImage'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => array('fieldType' => 'radio', 'files' => true, 'filesOnly' => true, 'extensions' => 'jpg,jpeg,gif,png', 'tl_class' => 'clr'),
            'sql' => "binary(16) NULL",
        ),
        'previewConsiderOrientation' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewConsiderOrientation'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => 'w50 m12'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'previewIcon' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewIcon'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => ' m12'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'previewExtension' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewExtension'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => ' m12'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'previewFilesizeD' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewFilesizeD'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => ' m12'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'previewFilesizeB' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewFilesizeB'],
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => ' m12'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'previewImageFloating' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewImageFloating'],
            'default' => 'above',
            'exclude' => true,
            'inputType' => 'radioTable',
            'options' => array('above', 'left', 'right', 'below'),
            'eval' => array('cols' => 4, 'tl_class' => 'w50'),
            'sql' => "varchar(32) NOT NULL default ''"
        ),
        'previewTemplate' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_settings']['previewTemplate'],
            'default' => 'ce_download_extended',
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => array('download_extended_c', 'getTemplates'),
            'eval' => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
            'sql' => "varchar(64) NOT NULL default ''"
        ),
    )
);

class download_extended_c extends Backend
{

    public function getRowLabel($row)
    {
        if ($row['previewStandardImage'])
        {
            $objFile = \FilesModel::findById($row['previewStandardImage']);

            if ($objFile !== null)
            {
                $preview = $objFile->path;
                $image = '<img src="' . $this->getImage($preview, 65, 45, 'center_center') . '" alt="' . htmlspecialchars($label) . '" style="display: inline-block;vertical-align: top;*display:inline;zoom:1;padding-right:8px;" />';
            }
        }

        if ($row['title'])
        {
            $text = '<span class="name"><strong>' . $row['title'] . '</strong></span>';
        }

        if ($row['previewImageSize'])
        {
            $arrTemp = deserialize($row['previewImageSize']);
            $text .= '&nbsp;- <span >' . $arrTemp[0] . ', ' . $arrTemp[1] . ', ' . $arrTemp[2] . '</span>';
        }
        //'fields' => array('title', 'previewImageSize', 'previewImageMargin', 'previewConsiderOrientation'),


        return $text . $image;


        // return $this->replaceInsertTags('{{image::/' . $objFile->path . '?width=55&height=65}}');
    }

    public function previewDelete()
    {


        $objData = $this->Database->execute("SELECT a.id, f.path FROM tl_content a, tl_files f WHERE a.singleSRC = f.uuid AND a.type = 'download_extended'");



        if ($objData->numRows)
        {
            while ($objData->next())
            {

                $preview = 'system/tmp/' . $objData->id . '-' . substr(md5($objData->path), 0, 8) . '.jpg';
                // delete file
                if (file_exists(TL_ROOT . '/' . $preview))
                {
                    $delFile = new \File($preview);
                    $delFile->delete();
                }
            }
        }
        
    }

    /**
     * Return all event templates as array
     * @param object
     * @return array
     */
    public function getTemplates(DataContainer $dc)
    {
        return Controller::getTemplateGroup('ce_download_');
    }

}
