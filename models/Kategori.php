<?php

namespace app\models;

use yii\base\Model;

class Kategori extends Model
{
    public $nama;
    public $uri;
    public $slug;
    public $image_url;

    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama', 'uri', 'slug', 'image_url'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nama' => 'Nama Kategori',
            'uri' => 'URI Gambar',
            'slug' => 'Slug',
            'image_url' => 'URL Gambar',
        ];
    }

    /**
     * Create Kategori model from array data
     * @param array $data
     * @return Kategori
     */
    public static function fromArray($data)
    {
        $kategori = new self();
        $kategori->nama = $data['nama'] ?? '';
        $kategori->uri = $data['uri'] ?? '';
        $kategori->slug = $data['slug'] ?? '';
        $kategori->image_url = $data['image_url'] ?? '';
        
        return $kategori;
    }

    /**
     * Get display name (formatted)
     * @return string
     */
    public function getDisplayName()
    {
        return ucwords(strtolower($this->nama));
    }
}
