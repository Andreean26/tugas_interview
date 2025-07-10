<?php

namespace app\models;

/**
 * Model untuk data Best Banner
 */
class Best
{
    public $bn_id;
    public $bn_uri;
    public $slug;
    public $image_url;

    /**
     * Create Best object from API data array
     */
    public static function fromArray($data)
    {
        $best = new self();
        $best->bn_id = $data['bn_id'] ?? null;
        $best->bn_uri = $data['bn_uri'] ?? '';
        $best->slug = $data['slug'] ?? '';
        
        // Use the image_url from API or construct it
        $best->image_url = $data['image_url'] ?? 'https://ik.imagekit.io/uvfyddsfq/' . ltrim($best->bn_uri, '/');
        
        return $best;
    }

    /**
     * Get valid image URL with fallback
     */
    private static function getValidImageUrl($bn_uri)
    {
        if (empty($bn_uri)) {
            return self::getPlaceholderImage();
        }
        
        $baseUrl = 'https://gdaily.id/';
        $fullUrl = $baseUrl . ltrim($bn_uri, '/');
        
        // Check if image is accessible
        $headers = @get_headers($fullUrl, 1);
        if ($headers && strpos($headers[0], '200') !== false) {
            return $fullUrl;
        }
        
        return self::getPlaceholderImage();
    }

    /**
     * Get placeholder image for banner
     */
    private static function getPlaceholderImage()
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg width="300" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#e9ecef"/>
                <rect x="20" y="20" width="260" height="160" fill="#6c757d" rx="10"/>
                <text x="150" y="90" font-family="Arial, sans-serif" font-size="14" 
                      text-anchor="middle" fill="white">Best Banner</text>
                <text x="150" y="110" font-family="Arial, sans-serif" font-size="12" 
                      text-anchor="middle" fill="#dee2e6">Image Not Available</text>
                <circle cx="150" cy="130" r="15" fill="#495057"/>
                <text x="150" y="135" font-family="Arial, sans-serif" font-size="10" 
                      text-anchor="middle" fill="white">★</text>
            </svg>
        ');
    }

    /**
     * Get display title for the banner
     */
    public function getDisplayTitle()
    {
        if (!empty($this->slug)) {
            // Convert slug to readable title
            return ucwords(str_replace('-', ' ', $this->slug));
        }
        return 'Best Banner #' . $this->bn_id;
    }

    /**
     * Get banner URL (for linking if needed)
     */
    public function getBannerUrl()
    {
        return 'https://gdaily.id/' . $this->slug;
    }
}
