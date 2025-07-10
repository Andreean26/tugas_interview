<?php

namespace app\models;

/**
 * Model untuk data Brand
 */
class Brand
{
    public $nama;
    public $uri;
    public $slug;
    public $image_url;

    /**
     * Create Brand object from API data array
     */
    public static function fromArray($data)
    {
        $brand = new self();
        $brand->nama = $data['nama'] ?? '';
        $brand->uri = $data['uri'] ?? '';
        $brand->slug = $data['slug'] ?? '';
        
        // Use ImageKit URL directly
        $brand->image_url = 'https://ik.imagekit.io/uvfyddsfq/' . ltrim($brand->uri, '/');
        
        if (empty($brand->uri)) {
            $brand->image_url = self::getPlaceholderImage();
        }
        
        return $brand;
    }

    /**
     * Get valid image URL with fallback
     */
    private static function getValidImageUrl($uri)
    {
        if (empty($uri)) {
            return self::getPlaceholderImage();
        }
        
        $baseUrl = 'https://gdaily.id/';
        $fullUrl = $baseUrl . ltrim($uri, '/');
        
        // Check if image is accessible
        $headers = @get_headers($fullUrl, 1);
        if ($headers && strpos($headers[0], '200') !== false) {
            return $fullUrl;
        }
        
        return self::getPlaceholderImage();
    }

    /**
     * Get placeholder image for brand
     */
    private static function getPlaceholderImage()
    {
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg width="400" height="225" xmlns="http://www.w3.org/2000/svg">
                <rect width="100%" height="100%" fill="#e9ecef"/>
                <rect x="20" y="20" width="360" height="185" fill="#6c757d" rx="10"/>
                <text x="200" y="100" font-family="Arial, sans-serif" font-size="16" 
                      text-anchor="middle" fill="white">Brand Image</text>
                <text x="200" y="130" font-family="Arial, sans-serif" font-size="14" 
                      text-anchor="middle" fill="#dee2e6">Image Not Available</text>
                <path d="M180,150 h40 m-20,-20 v40" stroke="#495057" stroke-width="2"/>
            </svg>
        ');
    }

    /**
     * Get display name for the brand
     */
    public function getDisplayName()
    {
        if (!empty($this->nama)) {
            return $this->nama;
        }
        return ucwords(str_replace('-', ' ', $this->slug));
    }

    /**
     * Get brand URL (for linking if needed)
     */
    public function getBrandUrl()
    {
        return 'https://gdaily.id/' . $this->slug;
    }

    /**
     * Convert brand data array to Brand objects
     */
    public static function fromApiResponse($brandData)
    {
        if (empty($brandData) || !is_array($brandData)) {
            return [];
        }

        $brands = [];
        foreach ($brandData as $data) {
            if (is_object($data)) {
                $data = json_decode(json_encode($data), true);
            }
            $brands[] = self::fromArray($data);
        }

        return $brands;
    }
}
