<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class ProgramCarousel extends Widget
{
    public $programs = [];
    public $options = [];

    public function init()
    {
        parent::init();
        
        if (empty($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        
        Html::addCssClass($this->options, 'carousel slide');
        $this->options['data-bs-ride'] = 'carousel';
    }

    public function run()
    {
        if (empty($this->programs)) {
            return '<div class="alert alert-info">Tidak ada program tersedia.</div>';
        }

        $carouselId = $this->options['id'];
        
        ob_start();
        ?>
        <div <?= Html::renderTagAttributes($this->options) ?>>
            <div class="carousel-inner">
                <?php foreach ($this->programs as $index => $program): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="carousel-image-container" style="background: #f8f9fa; padding: 20px 0;">
                        <?= Html::img($program->image, [
                            'class' => 'd-block',
                            'alt' => Html::encode($program->title),
                            'style' => 'width: auto; max-height: 500px; margin: 0 auto; object-fit: contain;'
                        ]) ?>
                    </div>
                    <div class="program-info text-center py-3" style="background: white;">
                        <h5 class="mb-2" style="color: #2c3e50; font-weight: 600;"><?= Html::encode($program->title) ?></h5>
                        <p class="mb-0" style="color: #666;"><?= Html::encode($program->description) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($this->programs) > 1): ?>
            <!-- Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            
            <!-- Indicators -->
            <div class="carousel-indicators">
                <?php foreach ($this->programs as $index => $program): ?>
                <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $index ?>" 
                        <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?> 
                        aria-label="Slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
}
