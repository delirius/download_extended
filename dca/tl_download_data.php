<?php

/**
 * Table tl_download_data
 */
$GLOBALS['TL_DCA']['tl_download_data'] = array
    (
    // Config
    'config' => array
        (
        'dataContainer' => 'Table',
        'ptable' => 'tl_download_archive',
        'enableVersioning' => true,
        'sql' => array
            (
            'keys' => array
                (
                'id' => 'primary',
                'pid' => 'index'
            )
        )
    ),
    // List
    'list' => array
        (
        'sorting' => array
            (
            'mode' => 4,
            'fields' => array('sorting'),
            'headerFields' => array('title'),
            'panelLayout' => 'filter;search,limit',
            'child_record_callback' => array('tl_download_data_ext', 'getRowLabel')
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
                'label' => &$GLOBALS['TL_LANG']['tl_download_data']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif'
            ),
            'copy' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_data']['copy'],
                'href' => 'act=paste&amp;mode=copy',
                'icon' => 'copy.gif'
            ),
            'cut' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_data']['cut'],
                'href' => 'act=paste&amp;mode=cut',
                'icon' => 'cut.gif'
            ),
            'delete' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_data']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_data']['toggle'],
                'icon' => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_download_data_ext', 'toggleIcon')
            ),
            'show' => array
                (
                'label' => &$GLOBALS['TL_LANG']['tl_download_data']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif'
            )
        )
    ),
    // Palettes
    'palettes' => array
        (
        'default' => '{download_extended_legend},title,previewTemplate;{download_extended_image_legend},headline,previewStandardImage,previewImageSize,previewImageMargin,previewConsiderOrientation,previewImageFloating;{download_extended_imageg_legend},previewGenerateImage,pathImageMagick;{download_extended_text_legend},previewIcon,previewExtension,previewFilesizeD,previewFilesizeB;',
    ),
    // Fields
    'fields' => array
        (
        'id' => array
            (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
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
            'options_callback' => array('tl_download_data_ext', 'getTemplates'),
            'eval' => array('includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'),
            'sql' => "varchar(64) NOT NULL default ''"
        ),
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array
 */
class tl_download_data_ext extends Backend
{

    /**
     * Generate a song row and return it as HTML string
     * @param array
     * @return string
     */
    public function getRowLabel($row)
    {

        if ($row['orderSRC_01'])
        {
            $arrBilder = deserialize($row['orderSRC_01']);
            $objFile = \FilesModel::findById($arrBilder[0]);

            if ($objFile !== null)
            {
                $preview = $objFile->path;
                $image = '<img src="' . $this->getImage($preview, 65, 45, 'center_center') . '" alt="' . htmlspecialchars($label) . '" style="display: inline-block;vertical-align: top;*display:inline;zoom:1;padding-right:8px;" />';
            }
        }

        if ($row['title_01'])
        {
            $text = '<span class="name">' . $row['title_01'] . '</span>';
        }
        return $image . $text;


        // $out = $this->replaceInsertTags('{{image::/' . $row['vorschaubild'] . '?width=55&height=65}}');
    }

    public function generateAlias($varValue, DataContainer $dc)
    {

        $autoAlias = false;
        // Generate alias if there is none
        if (!strlen($varValue))
        {
            $autoAlias = true;
            $varValue = standardize($dc->activeRecord->title_01);
        }


        $objAlias = $this->Database->prepare("SELECT id FROM tl_download_data WHERE id=? OR alias=?")
                ->execute($dc->id, $varValue);
        // Check whether the page alias exists
        if ($objAlias->numRows > 1)
        {
            if (!$autoAlias)
            {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }
            $varValue .= '-' . $dc->id;
        }
        return $varValue;
    }

    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid')))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
            $this->redirect($this->getReferer());
        }



        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published'])
        {
            $icon = 'invisible.gif';
        }


        return '<a href="' . $this->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label) . '</a> ';
    }

    /**
     * Disable/enable a user group
     * @param integer
     * @param boolean
     */
    public function toggleVisibility($intId, $blnVisible)
    {
        // Check permissions to edit
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');



        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_page']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_page']['fields']['published']['save_callback'] as $callback)
            {
                $this->import($callback[0]);
                $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_download_data SET published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
                ->execute($intId);
    }

    public function pagePicker(DataContainer $dc)
    {
        return ' <a href="contao/page.php?do=' . Input::get('do') . '&amp;table=' . $dc->table . '&amp;field=' . $dc->field . '&amp;value=' . str_replace(array('{{link_url::', '}}'), '', $dc->value) . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']) . '" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])) . '\',\'url\':this.href,\'id\':\'' . $dc->field . '\',\'tag\':\'ctrl_' . $dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '') . '\',\'self\':this});return false">' . Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
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
