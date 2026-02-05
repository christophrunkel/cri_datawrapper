<?php

declare(strict_types=1);

namespace Cri\CriDatawrapper\Helper;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOEmbedHelper;
use TYPO3\CMS\Core\Utility\DebugUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Datawrapper helper class
 */
class DatawrapperHelper extends AbstractOEmbedHelper
{
    private const DATAWRAPPER_PUB = 'https://datawrapper.dwcdn.net/';
    private const DATAWRAPPER_URL = 'https://api.datawrapper.de/v3/';

    protected function getOEmbedUrl($mediaId, $format = 'json')
    {
        return self::DATAWRAPPER_URL."oembed?url=https://datawrapper.dwcdn.net/".trim($mediaId)."&format=$format";
               
    }

    public function transformUrlToFile($url, Folder $targetFolder)
    {
        $datawrapperid = null;
        // Try to get the Datawrapper code from given url.
        // https://www.soundlcoud.com/<username>/<path_segment>?parameter # Audio detail URL
        if(strlen($url)<50 && strpos(self::DATAWRAPPER_PUB,$url)==0)
        {
            $datawrapperid = substr(str_replace(self::DATAWRAPPER_PUB,'',$url),0,30);
            
        }

        return $this->transformMediaIdToFile($datawrapperid , $targetFolder, $this->extension);
    }

    /**
     * Transform mediaId to File
     *
     * We override the abstract function so that we can integrate our own handling for the title field
     *
     * @param string $mediaId
     * @param Folder $targetFolder
     * @param string $fileExtension
     * @return File
     */
    protected function transformMediaIdToFile($mediaId, Folder $targetFolder, $fileExtension)
    {
        $file = $this->findExistingFileByOnlineMediaId($mediaId, $targetFolder, $fileExtension);
        if ($file === null) {
            $fileName = $mediaId . '.' . $fileExtension;

            $oEmbed = $this->getOEmbedData($mediaId);
            if (!empty($oEmbed['title'])) {
                $title = $this->handleDatawrapperTitle($oEmbed['title']);
                if (!empty($title)) {
                    $fileName = $title . '.' . $fileExtension;
                }
            }
            $file = $this->createNewFile($targetFolder, $fileName, $mediaId);
        }
        return $file;
    }

    public function getPublicUrl(File $file, $relativeToCurrentScript = false)
    {
        // @deprecated $relativeToCurrentScript since v11, will be removed in TYPO3 v12.0
        $audioId = $this->getOnlineMediaId($file);

        return sprintf(self::DATAWRAPPER_URL . '%s', $audioId);
    }

    public function getPreviewImage(File $file)
    {
        $properties = $file->getProperties();
        $previewImageUrl = $properties['datawrapper_thumbnail_url'] ?? '';

        $datawrapperId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'datawrapper_' . md5($datawrapperId) . '.jpg';

        if (!empty($previewImageUrl)) {
            $previewImage = GeneralUtility::getUrl($previewImageUrl);
            file_put_contents($temporaryFileName, $previewImage);
            GeneralUtility::fixPermissions($temporaryFileName);
            return $temporaryFileName;
        }

        return '';
    }

    /**
     * Get meta data for OnlineMedia item
     * Using the meta data from oEmbed
     *
     * @param File $file
     * @return array with metadata
     */
    public function getMetaData(File $file)
    {
        $metaData = [];
        //die (print_r($this->getOnlineMediaId($file)));
        $mediaid= $this->getOnlineMediaId($file);
        $url = $this->getOEmbedUrl($mediaid, 'json');
        //$url="https://api.datawrapper.de/v3/oembed?url=https://datawrapper.dwcdn.net/".$mediaid."&format=json";
        
              
        

        //https://datawrapper.dwcdn.net/Bvi9d/838/
        
        $ch = curl_init();
        // IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
        // in most cases, you should set it to true
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);
        
        $oEmbed = (array)json_decode($result);
        
        
        
        

        //$oEmbed = $this->getOEmbedData($this->getOnlineMediaId($file));
       
        if ($oEmbed) {

             $metaData['width'] = $oEmbed['width'];
            // We only get the value "100%" from the oEmbed query
            // The 225 pixels come from the 16:9 format at 400 pixels
             $metaData['height'] = $oEmbed['height'];
            if (empty($file->getProperty('title'))) {
                $metaData['title'] = $this->handleDatawrapperTitle($oEmbed['title'] ?? "");
            }
            //$metaData['author'] = $oEmbed['author_name'];
            $metaData['datawrapper_html'] = $oEmbed['html'];
            $arr_media = explode("/",$mediaid);
            $metaData['datawrapper_thumbnail_url'] = "https://datawrapper.dwcdn.net/".$arr_media[0]."/plain-s.png?v=".$arr_media[1];
           //$metaData['soundcloud_thumbnail_url'] = "https://datawrapper.dwcdn.net/".$arr_media[0]."/plain-s.png?v=".$arr_media[1];
        }

        return $metaData;
    }

    /**
     * @param string $title
     * @return string
     */
    protected function handleDatawrapperTitle(string $title ="unbenannt"): string
    {
        return trim(mb_substr(strip_tags($title), 0, 255));
    }
}

