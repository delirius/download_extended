<?php

class downloadList extends \Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'download_list_default';

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            /** @var \BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['download_list'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }


        $file = \Input::get('file', true);

        if ($file != '')
        {

            $objData = \Database::getInstance()->prepare("SELECT download_list_archive FROM tl_module WHERE id = ?")->execute($this->id);

            if ($objData->numRows)
            {
                $arrIds = deserialize($objData->download_list_archive);
            }

            $objData = \Database::getInstance()->prepare("SELECT f.path FROM tl_download_data a, tl_files f WHERE a.linkSource = f.uuid AND a.published = 1 AND a.pid IN (" . implode(',', $arrIds) . ")")->execute();

            if ($objData->numRows)
            {
                $arrPaths = array();
                while ($objData->next())
                {
                    $arrPaths[] = $objData->path;
                }
            }

            // Send the file to the browser and do not send a 404 header (see #4632)
            if ($file != '' && in_array($file, $arrPaths))
            {
                \Controller::sendFileToBrowser($file);
            }
        }
        return parent::generate();
    }

    /**
     * Compile the current element
     */
    protected function compile()
    {

        $objPs = Database::getInstance()
                ->prepare("SELECT * FROM tl_download_settings WHERE 1")
                ->execute();

        while ($objPs->next())
        {
            $arrSetting[$objPs->id] = array('previewGenerateImage' => $objPs->previewGenerateImage, 'previewStandardImage' => $objPs->previewStandardImage, 'pathImageMagick' => $objPs->pathImageMagick, 'previewImageSize' => $objPs->previewImageSize, 'previewConsiderOrientation' => $objPs->previewConsiderOrientation,
                'previewImageMargin' => $objPs->previewImageMargin, 'previewImageFloating' => $objPs->previewImageFloating, 'previewIcon' => $objPs->previewIcon, 'previewExtension' => $objPs->previewExtension, 'previewFilesizeD' => $objPs->previewFilesizeD, 'previewFilesizeB' => $objPs->previewFilesizeB);
        }



        $objParams = Database::getInstance()
                ->prepare("SELECT * FROM tl_module WHERE id=?")
                ->limit(1)
                ->execute($this->id);

        if ($objParams->download_list_archive === '')
        {
            return false;
        }
        if ($objParams->download_per_page !== '')
        {
            $numberPerPage = $objParams->download_per_page;
        } else
        {
            $numberPerPage = 0;
        }

        if (\Input::get('pageStart') > 0)
        {
            $pageStart = \Input::get('pageStart') * $numberPerPage;
        } else
        {
            $pageStart = 0;
        }

        $arrCat = deserialize($objParams->download_list_archive);
        $strAnd = implode(',', $arrCat);


        //Wenn nötig, dann neues Template aktivieren
        if (($objParams->download_template != $this->strTemplate) && ($objParams->download_template != ''))
        {
            $this->strTemplate = $objParams->download_template;
            $this->Template = new FrontendTemplate($this->strTemplate);
        }



        $arrDownloadData = array();

        $query = ' SELECT SQL_CALC_FOUND_ROWS a.*, b.title as arc_title,b.description as arc_description, b.id as arc_id FROM tl_download_data a, tl_download_archive b WHERE a.pid=b.id  AND b.id IN (' . $strAnd . ') AND a.published = "1" ORDER BY FIELD(b.id,' . $strAnd . '), a.sorting';

        $objData = Database::getInstance()->prepare($query)->limit($numberPerPage, $pageStart)->execute();
        $objNum = Database::getInstance()->execute('SELECT FOUND_ROWS() as num');
        $query_cc = ' SELECT a.pid, COUNT(a.id) as cc FROM tl_download_data a WHERE 1 AND a.pid IN (' . $strAnd . ') AND a.published = "1" GROUP BY a.pid';
        $objCount = Database::getInstance()->prepare($query_cc)->execute();
        while ($objCount->next())
        {
            $arrCount[$objCount->pid] = $objCount->cc;
        }


        /* category */
        $query_cat = ' SELECT id,title FROM tl_download_data WHERE 1  AND published = "1" ';
        $objCat = Database::getInstance()->prepare($query_cat)->execute();
        while ($objCat->next())
        {
            $arrCat[$objCat->id] = $objCat->title;
        }


        $j = 0;
        while ($objData->next())
        {

            $j++;
            $countcat = $arrCount[$objData->pid];
            $class = ((($j % 2) == 0) ? ' even' : ' odd') . (($j == 1) ? ' first' : '');
            if ($j == $countcat)
            {
                $class .= ' last';
                $j = 0;
            }

            $objFileFind = \FilesModel::findById($objData->linkSource);
            $objFile = new \File($objFileFind->path, true);



            $allowedDownload = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['allowedDownload']));
            if (!in_array($objFile->extension, $allowedDownload))
            {
                return;
            }


            if (!strlen($objData->linkTitle))
            {
                $objData->linkTitle = $objFile->basename;
            }

            if (!$objFile->filename)
            {
                return;
            }

            $d_path = 'assets/images/download_extended/';

            if (!is_dir(TL_ROOT . '/' . $d_path))
            {
                mkdir(TL_ROOT . '/' . $d_path);
                if (!is_dir(TL_ROOT . '/' . $d_path))
                {
                    return false;
                }
            }

            $preview = $d_path . $objFile->filename . '-' . substr(md5($objFile->path), 0, 8) . '.jpg';
            
            if ($objData->previewImage)         // Preview image is given
            {
                $objImage = \FilesModel::findByUuid($objData->previewImage);

                if ($objImage !== null && is_file(TL_ROOT . '/' . $objImage->path))
                {
                    $preview = $objImage->path;
                }
            } elseif ($arrSetting[$objData->previewSettings]['previewGenerateImage'])         // Standard image
            {
                if (!is_file(TL_ROOT . '/' . $preview) || filemtime(TL_ROOT . '/' . $preview) < (time() - 604800)) // Image older than a week
                {
                    if (class_exists('Imagick', false) && !extension_loaded('imagick'))
                    {
                        //!@todo Imagick PHP-Funktionen verwenden, falls vorhanden
                    } else
                    {

                        $strFirst = ($objFile->extension == 'pdf' ? '[0]' : '' );
                        @exec(sprintf('PATH=\$PATH:%s;export PATH;%s/convert %s/%s' . $strFirst . ' %s/%s 2>&1', $arrSetting[$objData->previewSettings]['pathImageMagick'], $arrSetting[$objData->previewSettings]['pathImageMagick'], TL_ROOT, escapeshellarg($objFile->path), TL_ROOT, $preview), $convert_output, $convert_code);

                        if (!is_file(TL_ROOT . '/' . $preview))
                        {
                            $convert_output = implode("<br />", $convert_output);
                            $reason = 'ImageMagick Exit Code: ' . $convert_code;
                            if ($convert_code == 127)
                            {
                                $reason = 'ImageMagick is not available at ' . $arrSetting[$objData->previewSettings]['pathImageMagick'];
                            }
                            if (strpos($convert_output, 'gs: command not found'))
                            {
                                $reason = 'Unable to read PDF due to GhostScript error.';
                            }
                            $this->log('Creating preview from "' . $objFile->path . '" failed! ' . $reason . "\n\n" . $convert_output, 'ContentPreviewDownload compile()', TL_ERROR);
                            if (TL_MODE == 'BE')
                            {
                                $objData->linkTitle .= $GLOBALS['TL_LANG']['MSC']['creatingPreviewFailed'];
                            }
                        }
                    }
                }
            } elseif ($arrSetting[$objData->previewSettings]['previewStandardImage'])         // Standard image
            {
                $objImage = \FilesModel::findByUuid($arrSetting[$objData->previewSettings]['previewStandardImage']);

                if ($objImage !== null && is_file(TL_ROOT . '/' . $objImage->path))
                {
                    $preview = $objImage->path;
                }
            }



            if (is_file(TL_ROOT . '/' . $preview))
            {
//            $imgIndividualSize = deserialize($this->size);
                $imgDefaultSize = deserialize($arrSetting[$objData->previewSettings]['previewImageSize']);
                $arrImageSize = getimagesize(TL_ROOT . '/' . $preview);

                if ($imgIndividualSize[0] > 0 || $imgIndividualSize[1] > 0)
                { // individualSize
                    $imgSize = $imgIndividualSize;
                } elseif ($imgDefaultSize[0] > 0 || $imgDefaultSize[1] > 0)
                { // preferences -> defaultSize
                    if ($arrSetting[$objData->previewSettings]['previewConsiderOrientation'] && $arrImageSize[0] >= $arrImageSize[1])
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
                    $preview = $src;
                    $previewImgSize = ' ' . $imgSize[3];
                }
            }


            $arrMetainfo = array();
            if ($arrSetting[$objData->previewSettings]['previewExtension'])
            {
                $arrMetainfo['extension'] = strtoupper($objFile->extension);
            }

            if ($arrSetting[$objData->previewSettings]['previewFilesizeD'])
            {
                $intSize = $objFile->filesize;
                for ($i = 0; $intSize >= 1000; $i++)
                {
                    $intSize /= 1000;
                }

                $arrMetainfo['filesize_dec'] = static::getFormattedNumber($intSize, 1) . ' ' . str_replace(array('KiB', 'i'), array('kB', ''), $GLOBALS['TL_LANG']['UNITS'][$i]);
            }
            if ($arrSetting[$objData->previewSettings]['previewFilesizeB'])
            {
                $arrMetainfo['filesize_bin'] = $this->getReadableSize($objFile->filesize, 1);
            }





            $strHref = \Environment::get('request');
            // Remove an existing file parameter (see #5683)
            if (preg_match('/(&(amp;)?|\?)file=/', $strHref))
            {
                $strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
            }
            $strHref .= (($GLOBALS['TL_CONFIG']['disableAlias'] || strpos($strHref, '?') !== false) ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFile->path);







            $arrNew = array
                (
                'id' => trim($objData->id),
                'title' => trim($objData->title),
                'linkSource' => $objFile->path,
                'href' => $strHref,
                'linkTitle' => trim($objData->linkTitle),
                'titleText' => trim($objData->titleText),
                'previewSettings' => trim($objData->previewSettings),
                'description' => trim($objData->description),
                'preview' => $preview,
                'previewImgSize' => $previewImgSize,
                'arc_title' => trim($objData->arc_title),
                'arc_description' => trim($objData->arc_description),
                'arc_count' => $countcat,
                'class' => $class,
                'arrMetainfo' => $arrMetainfo,
            );


            $arrNew['margin'] = $this->generateMargin(deserialize($arrSetting[$objData->previewSettings]['previewImageMargin']), 'margin');

            // Float image
            if ($arrSetting[$objData->previewSettings]['previewImageFloating'])
            {
                $arrNew['floatClass'] = ' float_' . $arrSetting[$objData->previewSettings]['previewImageFloating'];
            }

            // Icon
            if ($arrSetting[$objData->previewSettings]['previewIcon'])
            {
                $arrNew['icon'] = TL_ASSETS_URL . 'assets/contao/images/' . $objFile->icon;
            }


            if ($objData->category_01 > 0)
            {
                $arrNew['category_01'] = $arrCat[$objData->category_01];
            }
            if ($objData->category_02 > 0)
            {
                $arrNew['category_02'] = $arrCat[$objData->category_02];
            }


            $arrDownloadData[$objData->arc_id][] = $arrNew;
        }
        $this->Template->arrDownloadData = $arrDownloadData;

        if ($numberPerPage > 0) // on/off
        {

            /* pagination */
            $strPager = '';
            if ($objNum->num > $numberPerPage)
            {
                $fcc = $objNum->num / $numberPerPage;
                $cc = floor($fcc);
                if ($fcc > $cc)
                {
                    $cc++;
                }
                $strPager .= '<ul class="pagination">';

                for ($i = 0; $i < $cc; $i++)
                {
                    $page = $i + 1;
                    if (\Input::get('pageStart') == $i)
                    {
                        $strPager .= '<li>';
                        $strPager .= '<a class="link current" href="' . $this->addToUrl('pageStart=' . $i) . '">' . $page . '</a>';
                        $strPager .= '</li>';
                    } else
                    {
                        $strPager .= '<li>';
                        $strPager .= '<a  class="link" href="' . $this->addToUrl('pageStart=' . $i) . '">' . $page . '</a>';
                        $strPager .= '</li>';
                    }
                }
                $strPager .= '</ul>';
            }

            $this->Template->pagination = $strPager;
        } // on/off
    }

// complile
}
