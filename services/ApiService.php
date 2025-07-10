<?php

namespace app\services;

use Yii;
use yii\httpclient\Client;
use app\helpers\ImageHelper;

class ApiService
{
    private $client;
    private $baseUrl = 'https://gdaily.id/api-test';
    private const IMAGEKIT_URL = 'https://ik.imagekit.io/uvfyddsfq/';

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Mengambil data dashboard dari API
     * @return array|null
     */
    public function getDashboardData()
    {
        try {
            $response = $this->client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->baseUrl . '/dashboard')
                ->send();

            if ($response->isOk) {
                // Log response untuk debugging
                Yii::info("API Response: " . json_encode($response->data), 'api');
                return $response->data;
            }

            return null;
        } catch (\Exception $e) {
            Yii::error("API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil data program khusus dari API dashboard
     * @return array
     */
    public function getProgramData()
    {
        $dashboardData = $this->getDashboardData();
        
        // Debugging: log the actual structure
        if ($dashboardData) {
            Yii::info("Dashboard Data Structure: " . json_encode($dashboardData), 'api');
        }
        
        // Try different possible structures
        $programData = null;
        
        // Structure 1: program.data (nested)
        if ($dashboardData && isset($dashboardData['program']) && isset($dashboardData['program']['data'])) {
            $programData = $dashboardData['program']['data'];
        }
        // Structure 2: direct program array
        elseif ($dashboardData && isset($dashboardData['program'])) {
            $programData = $dashboardData['program'];
        }
        // Structure 3: data is direct array
        elseif ($dashboardData && isset($dashboardData['data'])) {
            $programData = $dashboardData['data'];
        }
        
        if ($programData && is_array($programData)) {
            $programs = [];
            
            foreach ($programData as $index => $item) {
                // Handle both object and array format
                $bnId = is_array($item) ? ($item['bn_id'] ?? ($index + 1)) : ($item->bn_id ?? ($index + 1));
                $slug = is_array($item) ? ($item['slug'] ?? 'program-' . ($index + 1)) : ($item->slug ?? 'program-' . ($index + 1));
                $bnUri = is_array($item) ? ($item['bn_uri'] ?? '') : ($item->bn_uri ?? '');
                
                $programs[] = [
                    'id' => $bnId,
                    'title' => $this->generateTitleFromSlug($slug),
                    'description' => 'Program ' . $this->generateTitleFromSlug($slug),
                    'image' => self::IMAGEKIT_URL . ltrim($bnUri, '/'),
                    'slug' => $slug
                ];
            }
            
            return $programs;
        }

        // Return dummy data jika API tidak tersedia
        return [
            [
                'id' => 1,
                'title' => 'New Ugreen Juli 1',
                'description' => 'Program New Ugreen Juli 1',
                'image' => 'https://via.placeholder.com/800x400/0066cc/ffffff?text=New+Ugreen+Juli+1',
                'slug' => 'new-ugreen-juli-1'
            ],
            [
                'id' => 2,
                'title' => 'New Titon Juni 3',
                'description' => 'Program New Titon Juni 3',
                'image' => 'https://via.placeholder.com/800x400/cc6600/ffffff?text=New+Titon+Juni+3',
                'slug' => 'new-titon-juni-3'
            ],
            [
                'id' => 3,
                'title' => 'New Gdaily Juni 3A',
                'description' => 'Program New Gdaily Juni 3A',
                'image' => 'https://via.placeholder.com/800x400/009966/ffffff?text=New+Gdaily+Juni+3A',
                'slug' => 'new-gdaily-juni-3a'
            ]
        ];
    }

    /**
     * Generate title from slug
     * @param string $slug
     * @return string
     */
    private function generateTitleFromSlug($slug)
    {
        // Convert slug to readable title
        $title = str_replace(['-', '_'], ' ', $slug);
        return ucwords($title);
    }

    /**
     * Generate full image URL dengan validasi
     * @param string $uri
     * @return string
     */
    private function generateImageUrl($uri)
    {
        if (empty($uri)) {
            return $this->getPlaceholderImage('default');
        }

        // Hapus leading slash jika ada
        $uri = ltrim($uri, '/');
        
        // Gabungkan dengan ImageKit URL
        $imageUrl = self::IMAGEKIT_URL . $uri;
        
        // Coba cari URL yang accessible menggunakan ImageHelper
        $accessibleUrl = ImageHelper::findAccessibleImageUrl($imageUrl);
        
        if ($accessibleUrl) {
            return $accessibleUrl;
        }

        // Jika tidak ada yang accessible, gunakan placeholder
        $filename = basename($uri, '.jpg');
        return $this->getPlaceholderImage($filename);
    }

    /**
     * Generate placeholder image dengan warna yang berbeda
     * @param string $identifier
     * @return string
     */
    private function getPlaceholderImage($identifier)
    {
        $colors = [
            'banner_1107' => ['bg' => '4CAF50', 'text' => 'ffffff'],  // Green
            'banner_62015' => ['bg' => '2196F3', 'text' => 'ffffff'], // Blue
            'banner_65113' => ['bg' => 'FF9800', 'text' => 'ffffff'], // Orange
            'banner_3176' => ['bg' => '9C27B0', 'text' => 'ffffff'],  // Purple
            'banner_56238' => ['bg' => 'F44336', 'text' => 'ffffff'], // Red
            'banner_20709' => ['bg' => '795548', 'text' => 'ffffff'], // Brown
            'banner_34000' => ['bg' => '607D8B', 'text' => 'ffffff'], // Blue Grey
            'banner_41202' => ['bg' => 'FF5722', 'text' => 'ffffff'], // Deep Orange
            'banner_30173' => ['bg' => '3F51B5', 'text' => 'ffffff'], // Indigo
            'default' => ['bg' => '9E9E9E', 'text' => 'ffffff']       // Grey
        ];

        $color = $colors[$identifier] ?? $colors['default'];
        $text = str_replace(['banner_', '_'], [' ', ' '], $identifier);
        $text = ucwords($text);

        return "https://via.placeholder.com/800x400/{$color['bg']}/{$color['text']}?text=" . urlencode($text);
    }

    /**
     * Check if image URL is accessible
     * @param string $url
     * @return bool
     */
    private function isImageUrlAccessible($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            return $httpCode == 200;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Mengambil data kategori dari API
     * @return array|null
     */
    public function getKategoriData()
    {
        try {
            $response = $this->client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->baseUrl . '/dashboard-kategori')
                ->send();

            if ($response->isOk && isset($response->data['data'])) {
                Yii::info("Kategori API Response: " . json_encode($response->data), 'api');
                
                $categories = [];
                foreach ($response->data['data'] as $item) {
                    $itemData = json_decode(json_encode($item), true); // Convert stdClass to array
                    $uri = $itemData['uri'] ?? '';
                    
                    $categories[] = [
                        'nama' => $itemData['nama'] ?? '',
                        'uri' => $uri,
                        'slug' => $itemData['slug'] ?? '',
                        'image_url' => self::IMAGEKIT_URL . ltrim($uri, '/')
                    ];
                }
                return $categories;
            }
            return null;
        } catch (\Exception $e) {
            Yii::error("Kategori API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil data brand dari API dashboard
     * @return array|null
     */
    public function getBrandData()
    {
        try {
            $dashboardData = $this->getDashboardData();
            
            if ($dashboardData && isset($dashboardData['brand']['data'])) {
                $brands = [];
                foreach ($dashboardData['brand']['data'] as $item) {
                    $itemData = json_decode(json_encode($item), true); // Convert stdClass to array
                    $uri = $itemData['uri'] ?? '';
                    
                    // Pastikan URI tidak kosong
                    if (!empty($uri)) {
                        $uri = ltrim($uri, '/');
                        $brands[] = [
                            'nama' => $itemData['nama'] ?? '',
                            'uri' => $uri,
                            'slug' => $itemData['slug'] ?? '',
                            'image_url' => self::IMAGEKIT_URL . $uri
                        ];
                    }
                }
                return $brands;
            }
            return null;
        } catch (\Exception $e) {
            Yii::error("Brand API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate URL gambar untuk kategori
     * @param string $uri
     * @param string $nama
     * @return string
     */
    private function generateKategoriImageUrl($uri, $nama = '')
    {
        if (empty($uri)) {
            return $this->getKategoriPlaceholderImage($nama ?: 'kategori-default');
        }

        // Hapus leading slash jika ada
        $uri = ltrim($uri, '/');
        
        // Gabungkan dengan ImageKit URL
        $imageUrl = self::IMAGEKIT_URL . $uri;

        // Coba cari URL yang accessible menggunakan ImageHelper
        $accessibleUrl = ImageHelper::findAccessibleImageUrl($imageUrl);
        
        if ($accessibleUrl) {
            return $accessibleUrl;
        }

        // Jika tidak ada yang accessible, gunakan placeholder dengan nama kategori
        return $this->getKategoriPlaceholderImage($nama ?: basename($uri, '.jpg'));
    }

    /**
     * Generate placeholder khusus untuk kategori
     * @param string $categoryName
     * @return string
     */
    private function getKategoriPlaceholderImage($categoryName)
    {
        // Warna yang berbeda untuk kategori berdasarkan nama kategori
        $categoryColors = [
            'cable' => ['bg' => '2196F3', 'text' => 'ffffff', 'icon' => 'CABLE'],
            'adaptor' => ['bg' => '4CAF50', 'text' => 'ffffff', 'icon' => 'ADAPTOR'],
            'charger' => ['bg' => 'FF9800', 'text' => 'ffffff', 'icon' => 'CHARGER'],
            'earphone' => ['bg' => 'F44336', 'text' => 'ffffff', 'icon' => 'EARPHONE'],
            'headphone' => ['bg' => 'E91E63', 'text' => 'ffffff', 'icon' => 'HEADPHONE'],
            'wireless' => ['bg' => '9C27B0', 'text' => 'ffffff', 'icon' => 'WIRELESS'],
            'phone' => ['bg' => '795548', 'text' => 'ffffff', 'icon' => 'PHONE'],
            'computer' => ['bg' => '607D8B', 'text' => 'ffffff', 'icon' => 'COMPUTER'],
            'car' => ['bg' => 'FF5722', 'text' => 'ffffff', 'icon' => 'CAR'],
            'audio' => ['bg' => '3F51B5', 'text' => 'ffffff', 'icon' => 'AUDIO'],
            'speaker' => ['bg' => '8BC34A', 'text' => 'ffffff', 'icon' => 'SPEAKER'],
            'gaming' => ['bg' => 'E91E63', 'text' => 'ffffff', 'icon' => 'GAMING'],
            'powerbank' => ['bg' => 'FF6F00', 'text' => 'ffffff', 'icon' => 'POWERBANK'],
            'battery' => ['bg' => 'CDDC39', 'text' => '000000', 'icon' => 'BATTERY'],
            'case' => ['bg' => '009688', 'text' => 'ffffff', 'icon' => 'CASE'],
            'glass' => ['bg' => '00BCD4', 'text' => 'ffffff', 'icon' => 'GLASS'],
            'holder' => ['bg' => '673AB7', 'text' => 'ffffff', 'icon' => 'HOLDER'],
            'mouse' => ['bg' => '5C6BC0', 'text' => 'ffffff', 'icon' => 'MOUSE'],
            'keyboard' => ['bg' => '42A5F5', 'text' => 'ffffff', 'icon' => 'KEYBOARD'],
            'hub' => ['bg' => '66BB6A', 'text' => 'ffffff', 'icon' => 'HUB'],
            'converter' => ['bg' => 'AB47BC', 'text' => 'ffffff', 'icon' => 'CONVERTER'],
            'tws' => ['bg' => 'EC407A', 'text' => 'ffffff', 'icon' => 'TWS'],
            'microphone' => ['bg' => 'EF5350', 'text' => 'ffffff', 'icon' => 'MIC'],
            'tripod' => ['bg' => '26A69A', 'text' => 'ffffff', 'icon' => 'TRIPOD'],
            'fan' => ['bg' => '29B6F6', 'text' => 'ffffff', 'icon' => 'FAN'],
            'universal' => ['bg' => '78909C', 'text' => 'ffffff', 'icon' => 'UNIVERSAL'],
            'box' => ['bg' => '8D6E63', 'text' => 'ffffff', 'icon' => 'BOX'],
            'tools' => ['bg' => 'FFA726', 'text' => 'ffffff', 'icon' => 'TOOLS'],
            'default' => ['bg' => '9E9E9E', 'text' => 'ffffff', 'icon' => 'CATEGORY']
        ];

        // Cari kategori berdasarkan nama
        $categoryType = 'default';
        $lowerName = strtolower($categoryName);
        
        foreach ($categoryColors as $key => $color) {
            if (strpos($lowerName, $key) !== false) {
                $categoryType = $key;
                break;
            }
        }

        $color = $categoryColors[$categoryType];
        
        return "https://via.placeholder.com/300x200/{$color['bg']}/{$color['text']}?text=" . 
               urlencode($color['icon']);
    }

    /**
     * Mengambil data best banner dari API
     * @return array|null
     */
    public function getBestData()
    {
        try {
            $response = $this->client->createRequest()
                ->setMethod('GET')
                ->setUrl($this->baseUrl . '/dashboard')
                ->send();

            if ($response->isOk) {
                $data = $response->data;
                
                // Extract best data
                if (isset($data['best']) && isset($data['best']['data']) && is_array($data['best']['data'])) {
                    $bestData = [];
                    
                    foreach ($data['best']['data'] as $item) {
                        $bnUri = $item['bn_uri'] ?? '';
                        $bestData[] = [
                            'bn_id' => $item['bn_id'] ?? '',
                            'bn_uri' => $bnUri,
                            'image_url' => self::IMAGEKIT_URL . ltrim($bnUri, '/'),
                            'slug' => $item['slug'] ?? '',
                        ];
                    }
                    
                    return $bestData;
                }
            }

            return null;
        } catch (\Exception $e) {
            Yii::error("Best API Error: " . $e->getMessage());
            return null;
        }
    }
}
