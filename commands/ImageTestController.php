<?php

namespace app\commands;

use yii\console\Controller;
use app\services\ApiService;
use app\helpers\ImageHelper;

class ImageTestController extends Controller
{
    /**
     * Test semua URL gambar dari API
     */
    public function actionTestApiImages()
    {
        echo "Testing image URLs from API...\n\n";
        
        $apiService = new ApiService();
        $dashboardData = $apiService->getDashboardData();
        
        if (!$dashboardData || !isset($dashboardData['program']['data'])) {
            echo "❌ Failed to get API data\n";
            return;
        }

        $programs = $dashboardData['program']['data'];
        
        foreach ($programs as $program) {
            echo "Testing program: {$program['slug']}\n";
            echo "Banner URI: {$program['bn_uri']}\n";
            
            $testUrls = ImageHelper::generateTestUrls($program['bn_uri']);
            $results = ImageHelper::validateMultipleUrls($testUrls);
            
            $foundAccessible = false;
            foreach ($results as $url => $result) {
                $status = $result['accessible'] ? '✅' : '❌';
                $info = $result['accessible'] ? 
                    " (Content-Type: {$result['content_type']}, Size: {$result['file_size']} bytes)" : 
                    " (HTTP: {$result['status_code']}, Error: {$result['error']})";
                
                echo "  {$status} {$url}{$info}\n";
                
                if ($result['accessible']) {
                    $foundAccessible = true;
                }
            }
            
            if (!$foundAccessible) {
                echo "  ⚠️  No accessible URLs found, will use placeholder\n";
            }
            
            echo "\n";
        }
    }
    
    /**
     * Test specific image URL
     * @param string $url
     */
    public function actionTestSingle($url)
    {
        echo "Testing single URL: {$url}\n\n";
        
        $result = ImageHelper::validateImageUrl($url);
        
        echo "Accessible: " . ($result['accessible'] ? 'YES ✅' : 'NO ❌') . "\n";
        echo "Status Code: {$result['status_code']}\n";
        echo "Content Type: {$result['content_type']}\n";
        echo "File Size: {$result['file_size']} bytes\n";
        
        if ($result['error']) {
            echo "Error: {$result['error']}\n";
        }
    }

    /**
     * Test specific banner URI from API format
     * @param string $bnUri
     */
    public function actionTestBanner($bnUri = 'uploads/baners/banner_1107.jpg')
    {
        echo "Testing banner URI: {$bnUri}\n\n";
        
        $testUrls = ImageHelper::generateTestUrls($bnUri);
        echo "Generated test URLs:\n";
        
        foreach ($testUrls as $url) {
            echo "- {$url}\n";
        }
        
        echo "\nTesting URLs...\n\n";
        
        $results = ImageHelper::validateMultipleUrls($testUrls);
        
        foreach ($results as $url => $result) {
            $status = $result['accessible'] ? '✅ ACCESSIBLE' : '❌ NOT ACCESSIBLE';
            echo "{$status}: {$url}\n";
            
            if ($result['accessible']) {
                echo "  Content-Type: {$result['content_type']}\n";
                echo "  File Size: {$result['file_size']} bytes\n";
            } else {
                echo "  HTTP Code: {$result['status_code']}\n";
                echo "  Error: {$result['error']}\n";
            }
            echo "\n";
        }
        
        // Test find accessible URL
        $accessibleUrl = ImageHelper::findAccessibleImageUrl($bnUri);
        if ($accessibleUrl) {
            echo "✅ Found accessible URL: {$accessibleUrl}\n";
        } else {
            echo "❌ No accessible URL found for this banner\n";
        }
    }

    /**
     * Test kategori API
     */
    public function actionTestKategori()
    {
        echo "Testing kategori API...\n\n";
        
        $apiService = new ApiService();
        $kategoriData = $apiService->getKategoriData();
        
        if (!$kategoriData) {
            echo "❌ Failed to get kategori data\n";
            return;
        }

        echo "✅ Found " . count($kategoriData) . " categories\n\n";
        
        foreach (array_slice($kategoriData, 0, 5) as $index => $kategori) {
            echo "Category " . ($index + 1) . ":\n";
            echo "  Nama: {$kategori['nama']}\n";
            echo "  URI: {$kategori['uri']}\n";
            echo "  Slug: {$kategori['slug']}\n";
            echo "  Image URL: {$kategori['image_url']}\n";
            
            // Test image URL
            $result = ImageHelper::validateImageUrl($kategori['image_url']);
            $status = $result['accessible'] ? '✅ Accessible' : '❌ Not accessible';
            echo "  Image Status: {$status}\n";
            
            echo "\n";
        }
        
        echo "... and " . (count($kategoriData) - 5) . " more categories\n";
    }
}
