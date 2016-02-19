<?php

/**
 * Add palettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['download_list'] = '{title_legend},name,type;{download_legend},download_list_archive,download_template,download_per_page;';


/**
 * Add fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['download_list_archive'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_module']['download_list_archive'],
    'exclude' => true,
    'inputType' => 'checkboxWizard',
    'foreignKey' => 'tl_download_archive.title',
    'eval' => array('multiple' => true, 'mandatory' => false,'tl_class' => 'm12'),
    'sql' => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['download_per_page'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_module']['download_per_page'],
    'exclude' => true, // Zugang fuer Benutzer
    'inputType' => 'text',
    'eval' => array('mandatory' => false, 'maxlength' => 12, 'tl_class' => 'w50'),
    'sql' => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['download_template'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_module']['download_template'],
    'default' => 'slogan_standard',
    'exclude' => true,
    'inputType' => 'select',
    'options_callback' => array('tl_module_download', 'getTemplates'),
    'eval' => array('tl_class' => 'm12'),
    'sql' => "varchar(255) NOT NULL default ''"
);

class tl_module_download extends Backend
{

    /**
     * Return all event templates as array
     * @param object
     * @return array
     */
    public function getTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup('download_');
    }

}

?>