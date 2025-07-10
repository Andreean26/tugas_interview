<?php

namespace app\helpers;

use Yii;
use yii\httpclient\Client;

class ImageHelper
{
    /**
     * Validate apakah URL gambar dapat diakses
     * @param string $url
     * @param int $timeout
     * @return array ['accessible' => bool, 'status_code' => int, 'error' => string|null]
     */
    public static function validateImageUrl($url, $timeout = 5)
    {
        $result = [
            'accessible' => false,
            'status_code' => 0,
            'error' => null,
            'content_type' => null,
            'file_size' => 0
        ];

        try {
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('HEAD') // HEAD request lebih efisien
                ->setUrl($url)
                ->setOptions([
                    'timeout' => $timeout,
                    'followLocation' => true,
                    'sslVerifyPeer' => false,
                    'userAgent' => 'Mozilla/5.0 (compatible; YiiImageValidator/1.0)'
                ])
                ->send();

            $result['status_code'] = $response->statusCode;
            $result['accessible'] = $response->statusCode === 200;
            
            if ($response->statusCode === 200) {
                $headers = $response->headers;
                $result['content_type'] = $headers->get('content-type');
                $result['file_size'] = (int)$headers->get('content-length', 0);
                
                // Validate jika benar-benar image
                if (!self::isValidImageContentType($result['content_type'])) {
                    $result['accessible'] = false;
                    $result['error'] = 'Not a valid image content type: ' . $result['content_type'];
                }
            }

        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            Yii::warning("Image URL validation failed: {$url} - " . $e->getMessage(), 'image');
        }

        return $result;
    }

    /**
     * Batch validate multiple URLs
     * @param array $urls
     * @return array
     */
    public static function validateMultipleUrls($urls)
    {
        $results = [];
        
        foreach ($urls as $url) {
            $results[$url] = self::validateImageUrl($url);
        }
        
        return $results;
    }

    /**
     * Cek apakah content type adalah image yang valid
     * @param string $contentType
     * @return bool
     */
    private static function isValidImageContentType($contentType)
    {
        if (!$contentType) return false;
        
        $validTypes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml'
        ];

        return in_array(strtolower($contentType), $validTypes);
    }

    /**
     * Generate test URLs untuk banner dari API
     * @param string $bnUri
     * @return array
     */
    public static function generateTestUrls($bnUri)
    {
        return [
            'https://gdaily.id/' . $bnUri,
            'https://gdaily.id/public/' . $bnUri,
            'https://gdaily.id/storage/' . $bnUri,
            'https://gdaily.id/assets/' . $bnUri,
            'https://gdaily.id/images/' . $bnUri,
            'https://gdaily.id/media/' . $bnUri,
        ];
    }

    /**
     * Cari URL gambar yang accessible dari list kemungkinan
     * @param string $bnUri
     * @return string|null
     */
    public static function findAccessibleImageUrl($bnUri)
    {
        $testUrls = self::generateTestUrls($bnUri);
        
        foreach ($testUrls as $url) {
            $result = self::validateImageUrl($url);
            if ($result['accessible']) {
                Yii::info("Found accessible image URL: {$url}", 'image');
                return $url;
            }
        }
        
        return null;
    }
}
