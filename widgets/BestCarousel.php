<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class BestCarousel extends Widget
{
    public $bests = [];
    public $options = [];

    public function run()
    {
        if (empty($this->bests)) {
            return '<div class="alert alert-info">Tidak ada banner best tersedia</div>';
        }

        $id = $this->options['id'] ?? 'bestCarousel';
        
        $content = Html::beginTag('div', [
            'id' => $id,
            'class' => 'carousel slide',
            'data-bs-ride' => 'carousel',
            'style' => $this->options['style'] ?? ''
        ]);

        // Carousel indicators
        $content .= '<div class="carousel-indicators">';
        foreach ($this->bests as $index => $best) {
            $content .= Html::tag('button', '', [
                'type' => 'button',
                'data-bs-target' => "#$id",
                'data-bs-slide-to' => $index,
                'class' => $index === 0 ? 'active' : '',
                'aria-current' => $index === 0 ? 'true' : 'false',
                'aria-label' => 'Slide ' . ($index + 1)
            ]);
        }
        $content .= '</div>';

        // Carousel inner
        $content .= '<div class="carousel-inner">';
        foreach ($this->bests as $index => $best) {
            $itemClass = 'carousel-item' . ($index === 0 ? ' active' : '');
            
            $content .= Html::beginTag('div', ['class' => $itemClass]);
            $content .= Html::img($best->image_url, [
                'class' => 'd-block w-100',
                'alt' => Html::encode($best->getDisplayTitle()),
                'style' => 'height: 400px; object-fit: cover;'
            ]);
            
            // Carousel caption
            $content .= '<div class="carousel-caption d-none d-md-block">';
            $content .= Html::tag('h5', Html::encode($best->getDisplayTitle()), [
                'class' => 'mb-2',
                'style' => 'text-shadow: 2px 2px 4px rgba(0,0,0,0.8);'
            ]);
            $content .= Html::tag('p', 'Best Banner ' . $best->bn_id, [
                'style' => 'text-shadow: 1px 1px 2px rgba(0,0,0,0.8);'
            ]);
            $content .= '</div>';
            
            $content .= Html::endTag('div');
        }
        $content .= '</div>';

        // Carousel controls
        $content .= Html::button(Html::tag('span', '', [
            'class' => 'carousel-control-prev-icon',
            'aria-hidden' => 'true'
        ]) . Html::tag('span', 'Previous', ['class' => 'visually-hidden']), [
            'class' => 'carousel-control-prev',
            'type' => 'button',
            'data-bs-target' => "#$id",
            'data-bs-slide' => 'prev'
        ]);

        $content .= Html::button(Html::tag('span', '', [
            'class' => 'carousel-control-next-icon',
            'aria-hidden' => 'true'
        ]) . Html::tag('span', 'Next', ['class' => 'visually-hidden']), [
            'class' => 'carousel-control-next',
            'type' => 'button',
            'data-bs-target' => "#$id",
            'data-bs-slide' => 'next'
        ]);

        $content .= Html::endTag('div');

        return $content;
    }
}
