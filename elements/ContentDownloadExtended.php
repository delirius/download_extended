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
class ContentDownloadExtended extends ContentElement
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'ce_download_extended';

    /**
     * Return if the file does not exist
     * @return string
     */
    public function generate()
    {
        // Return if there is no file
        if ($this->singleSRC == '')
        {
            return '';
        }

        $objFile = \FilesModel::findByUuid($this->singleSRC);

        if ($objFile === null)
        {
            if (!\Validator::isUuid($this->singleSRC))
            {
                return '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
            }
            return '';
        }

        $allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

        // Return if the file type is not allowed
        if (!in_array($objFile->extension, $allowedDownload))
        {
            return '';
        }

        $file = \Input::get('file', true);

        // Send the file to the browser and do not send a 404 header (see #4632)
        if ($file != '' && $file == $objFile->path)
        {
            \Controller::sendFileToBrowser($file);
        }

        $this->singleSRC = $objFile->path;
        return parent::generate();
    }

    /**
     * Generate content element
     */
    protected function compile()
    {
        if (!$this->previewSettings)
        {
            return '';
        }

        $objParams = $this->Database->prepare("SELECT * FROM tl_download_settings WHERE id = ?")->limit(1)->execute($this->previewSettings);

        if (!$objParams->previewImageSize)
        {
            return '';
        }

        if ($objParams->previewTemplate == '')
        {
            $objParams->previewTemplate = 'ce_download_extended';
        }
        $this->Template = new FrontendTemplate($objParams->previewTemplate);

        $objFile = new \File($this->singleSRC, true);
        $allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));

        if (!in_array($objFile->extension, $allowedDownload))
        {
            return;
        }

        if (!strlen($this->linkTitle))
        {
            $this->linkTitle = $objFile->basename;
        }


        $preview = 'system/tmp/' . $this->id . '-' . substr(md5($this->singleSRC), 0, 8) . '.jpg';

        if ($this->previewImage)         // Preview image is given
        {
            $objImage = \FilesModel::findByUuid($this->previewImage);

            if ($objImage !== null && is_file(TL_ROOT . '/' . $objImage->path))
            {
                $preview = $objImage->path;
            }
        } elseif ($objParams->previewGenerateImage)         // Standard image
        {
            if (!is_file(TL_ROOT . '/' . $preview) || filemtime(TL_ROOT . '/' . $preview) < (time() - 604800)) // Image older than a week
            {
                if (class_exists('Imagick', false) && !extension_loaded('imagick'))
                {
                    //!@todo Imagick PHP-Funktionen verwenden, falls vorhanden
                } else
                {

                    $strFirst = ($objFile->extension == 'pdf' ? '[0]' : '' );
                    @exec(sprintf('PATH=\$PATH:%s;export PATH;%s/convert %s/%s' . $strFirst . ' %s/%s 2>&1', $objParams->pathImageMagick, $objParams->pathImageMagick, TL_ROOT, escapeshellarg($this->singleSRC), TL_ROOT, $preview), $convert_output, $convert_code);

                    if (!is_file(TL_ROOT . '/' . $preview))
                    {
                        $convert_output = implode("<br />", $convert_output);
                        $reason = 'ImageMagick Exit Code: ' . $convert_code;
                        if ($convert_code == 127)
                        {
                            $reason = 'ImageMagick is not available at ' . $objParams->pathImageMagick;
                        }
                        if (strpos($convert_output, 'gs: command not found'))
                        {
                            $reason = 'Unable to read PDF due to GhostScript error.';
                        }
                        $this->log('Creating preview from "' . $this->singleSRC . '" failed! ' . $reason . "\n\n" . $convert_output, 'ContentPreviewDownload compile()', TL_ERROR);
                        if (TL_MODE == 'BE')
                        {
                            $this->linkTitle .= $GLOBALS['TL_LANG']['MSC']['creatingPreviewFailed'];
                        }
                    }
                }
            }
        } elseif ($objParams->previewStandardImage)         // Standard image
        {
            $objImage = \FilesModel::findByUuid($objParams->previewStandardImage);

            if ($objImage !== null && is_file(TL_ROOT . '/' . $objImage->path))
            {
                $preview = $objImage->path;
            }
        }




        if (is_file(TL_ROOT . '/' . $preview))
        {
//            $imgIndividualSize = deserialize($this->size);
            $imgDefaultSize = deserialize($objParams->previewImageSize);
            $arrImageSize = getimagesize(TL_ROOT . '/' . $preview);

            if ($imgIndividualSize[0] > 0 || $imgIndividualSize[1] > 0)
            { // individualSize
                $imgSize = $imgIndividualSize;
            } elseif ($imgDefaultSize[0] > 0 || $imgDefaultSize[1] > 0)
            { // preferences -> defaultSize
                if ($objParams->previewConsiderOrientation && $arrImageSize[0] >= $arrImageSize[1])
                {
                    $imgSize = array($imgDefaultSize[1], $imgDefaultSize[0], $imgDefaultSize[2]);
                } else
                {
                    $imgSize = $imgDefaultSize;
                }
            } else
            {
                $imgSize = array(0, 0, 'proportional');
            }



            $src = $this->getImage($preview, $imgSize[0], $imgSize[1], $imgSize[2]);

            if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
            {
                $this->Template->preview = $src;
                $this->Template->previewImgSize = ' ' . $imgSize[3];
            }
        }


        $arrMetainfo = array();
        if ($objParams->previewExtension)
        {
            $arrMetainfo['extension'] = strtoupper($objFile->extension);
        }

        if ($objParams->previewFilesizeD)
        {
            $intSize = $objFile->filesize;
            for ($i = 0; $intSize >= 1000; $i++)
            {
                $intSize /= 1000;
            }

            $arrMetainfo['filesize_dec'] = static::getFormattedNumber($intSize, 1) . ' ' . str_replace(array('KiB', 'i'), array('kB', ''), $GLOBALS['TL_LANG']['UNITS'][$i]);
        }
        if ($objParams->previewFilesizeB)
        {
            $arrMetainfo['filesize_bin'] = $this->getReadableSize($objFile->filesize, 1);
        }






        $strHref = \Environment::get('request');

        // Remove an existing file parameter (see #5683)
        if (preg_match('/(&(amp;)?|\?)file=/', $strHref))
        {
            $strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
        }

        $strHref .= (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($strHref, '?') !== false) ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFile->value);

        $this->Template->margin = $this->generateMargin(deserialize($objParams->previewImageMargin), 'margin');

        // Float image
        if ($objParams->previewImageFloating)
        {
            $this->Template->floatClass = ' float_' . $objParams->previewImageFloating;
        }
        // Icon
        if ($objParams->previewIcon)
        {
            $this->Template->icon = TL_ASSETS_URL . 'assets/contao/images/' . $objFile->icon;
        }
        if ($objParams->previewFilesizeD)
        {
            $this->Template->filesize = $arrMetainfo['filesize_dec'];
        } elseif ($objParams->previewFilesizeB)
        {
            $this->Template->filesize = $arrMetainfo['filesize_bin'];
        }


        $this->Template->link = $this->linkTitle;
        $this->Template->description = $this->description;
        $this->Template->title = specialchars($this->titleText ? : $this->linkTitle);
        $this->Template->href = $strHref;
        $this->Template->mime = $objFile->mime;
        $this->Template->extension = $objFile->extension;
        $this->Template->arrMetainfo = $arrMetainfo;
        $this->Template->path = $objFile->dirname;
    }

}
