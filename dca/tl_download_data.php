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
        'default' => '{text_legend},published,title;{download_extended_source_legend},linkSource;{download_extended_text_legend},linkTitle,titleText,description;{download_extended_image_legend},previewImage,previewSettings;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop',
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
        'sorting' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['sorting'],
            'inputType' => 'text',
            'eval' => array('tl_class' => 'w50'),
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array
            (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'published' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['published'],
            'inputType' => 'checkbox',
            'eval' => array('tl_class' => 'long'),
            'sql' => "char(1) NOT NULL default ''"
        ),
        'title' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['title'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => ''),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'linkSource' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['linkSource'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => array('fieldType' => 'radio', 'files' => true, 'filesOnly' => true, 'extensions' => $GLOBALS['TL_CONFIG']['allowedDownload'],'mandatory' => true),
            'sql' => "binary(16) NULL"
        ),
        'linkTitle' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['linkTitle'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'titleText' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['titleText'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql' => "varchar(255) NOT NULL default ''"
        ),
        'previewImage' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['previewImage'],
            'exclude' => true,
            'inputType' => 'fileTree',
            'eval' => array('fieldType' => 'radio', 'files' => true, 'filesOnly' => true, 'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes'], 'tl_class' => 'clr'),
            'sql' => "binary(16) NULL",
        ),
        'previewSettings' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['previewSettings'],
            'exclude' => true,
            'inputType' => 'select',
            'options_callback' => array('tl_download_data_ext', 'getDownloadSettings'),
            'eval' => array('chosen' => true, 'mandatory' => true),
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'description' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_download_data']['description'],
            'exclude' => true,
            'inputType' => 'textarea',
            'eval' => array('style' => 'height:48px', 'tl_class' => 'clr', 'rte' => 'tinyMCE'),
            'sql' => "text NULL"
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
       public function getRowLabel($row) {


        if ($row['linkSource']) {

            $objFileFind = \FilesModel::findById($row['linkSource']);
            $objFile = new \File($objFileFind->path, true);



            if ($row['previewImage'] != '') {
                $objFileFind = \FilesModel::findById($row['previewImage']);
                $preview = '' . $objFileFind->path;
            } else {
                $preview = 'assets/images/download_extended/' . $objFile->filename . '-' . substr(md5($objFile->path), 0, 8) . '.jpg';
            }

            $image = '<img src="' . $this->getImage($preview, 110, 110, 'proportional') . '" alt="' . htmlspecialchars($label) . '" style="display: inline-block;vertical-align: top;*display:inline;zoom:1;margin-right:8px;border:1px solid #ccc;" />';
        }

        $text = '<div class="name" style="display: inline-block;vertical-align: top;">';

        if ($row['title']) {
            $text .= '<strong>' . $row['title'] . '</strong>';
        }
        if ($objFile->path) {
            $text .= '<br>' . $objFile->path;
            $path = preg_replace('/[^a-zA-Z0-9_\/.]/', '', $objFile->path);
            if ($path !== $objFile->path) {
                $text .= '<br><span style="color:#cc0000">File name is not web compatible</span>';
            }
        }
        $text .= '</div>';

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
