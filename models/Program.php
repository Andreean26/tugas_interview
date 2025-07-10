<?php

namespace app\models;

use yii\base\Model;

class Program extends Model
{
    public $id;
    public $title;
    public $description;
    public $image;
    public $slug;

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['id'], 'integer'],
            [['title', 'description', 'image', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Judul Program',
            'description' => 'Deskripsi',
            'image' => 'Gambar',
            'slug' => 'Slug',
        ];
    }

    /**
     * Create Program model from array data
     * @param array $data
     * @return Program
     */
    public static function fromArray($data)
    {
        $program = new self();
        $program->id = $data['id'] ?? null;
        $program->title = $data['title'] ?? '';
        $program->description = $data['description'] ?? '';
        $program->image = $data['image'] ?? '';
        $program->slug = $data['slug'] ?? '';
        
        return $program;
    }
}
