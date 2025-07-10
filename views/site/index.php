<?php

/** @var yii\web\View $this */
/** @var app\models\Program[] $programs */
/** @var app\models\Kategori[] $categories */
/** @var int $totalCategories */

use app\widgets\ProgramCarousel;
use app\widgets\BestCarousel;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    
    <!-- Program Carousel -->
    <?= ProgramCarousel::widget([
        'programs' => $programs,
        'options' => [
            'id' => 'programCarousel',
            'style' => 'margin-bottom: 30px;'
        ]
    ]) ?>
    
    
    
    <div class="program-info mt-4">
        <h3>Kategori</h3>
        <?php if (!empty($categories)): ?>
        <div class="row" id="kategoriesContainer">
            <?php foreach ($categories as $category): ?>
            <div class="col-md-3 col-sm-4 col-6 mb-3">
                <div class="card h-100 kategori-card">
                    <div class="card-img-container square-container">
                        <?= Html::img($category->image_url, [
                            'class' => 'card-img-top',
                            'alt' => Html::encode($category->nama),
                            'style' => 'width: 100%; height: 100%; object-fit: cover;'
                        ]) ?>
                    </div>
                    <div class="card-body d-flex flex-column p-2">
                        <h6 class="card-title text-center mb-0" style="font-size: 0.8rem; line-height: 1.2;">
                            <?= Html::encode($category->getDisplayName()) ?>
                        </h6>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($totalCategories > 8): ?>
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary" id="loadMoreCategories">
                <span class="button-text">Lihat Kategori Lainnya (<?= $totalCategories - 8 ?> kategori)</span>
                <span class="loading-text" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat...
                </span>
            </button>
            <button class="btn btn-outline-primary" id="showLessCategories" style="display: none;">
                <span class="button-text">Tampilkan Lebih Sedikit</span>
            </button>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Kategori sedang dimuat...
        </div>
        <?php endif; ?>
    </div>

    <!-- Best Banner Carousel -->
        <?= BestCarousel::widget([
            'bests' => $bests,
            'options' => [
                'id' => 'bestCarousel',
                'style' => 'margin-bottom: 30px; margin-top: 20px; border-radius: 15px; overflow: hidden; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);'
            ]
        ]) ?>

    <div class="program-info mt-4">
        <h3>Brand</h3>
        <?php if (!empty($brands)): ?>
        <div class="row" id="brandsContainer">
            <?php 
            // Take only the first 4 brands
            $displayBrands = array_slice($brands, 0, 4);
            foreach ($displayBrands as $brand): 
            ?>
            <div class="col-md-6 col-sm-6 col-12 mb-3">
                <div class="card h-100 brand-card">
                    <div class="card-img-container rectangular-container">
                        <?= Html::img($brand->image_url, [
                            'class' => 'card-img-top',
                            'alt' => Html::encode($brand->nama),
                            'style' => 'width: 100%; height: 100%; object-fit: cover;'
                        ]) ?>
                    </div>
                    <div class="card-body d-flex flex-column p-2">
                        <h6 class="card-title text-center mb-0" style="font-size: 0.9rem; line-height: 1.2;">
                            <?= Html::encode($brand->getDisplayName()) ?>
                        </h6>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (count($brands) > 4): ?>
        <div class="text-center mt-3 mb-4">
            <button class="btn btn-outline-primary" id="loadMoreBrands">
                <span class="button-text">Lihat Brand Lainnya (<?= count($brands) - 4 ?> brand)</span>
                <span class="loading-text" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i> Memuat...
                </span>
            </button>
            <button class="btn btn-outline-primary" id="showLessBrands" style="display: none;">
                <span class="button-text">Tampilkan Lebih Sedikit</span>
            </button>
        </div>
        <?php endif; ?>
        
        <!-- Empty Carousel -->
        <div id="emptyCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                        <p class="text-muted">Slide 1</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                        <p class="text-muted">Slide 2</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="d-flex justify-content-center align-items-center bg-light" style="height: 200px;">
                        <p class="text-muted">Slide 3</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#emptyCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#emptyCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Brand sedang dimuat...
        </div>
        <?php endif; ?>
    </div>
    
</div>

<style>
.kategori-card, .brand-card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    cursor: pointer;
    background: var(--card-gradient);
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.kategori-card:hover, .brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.square-container {
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 10px;
}

.square-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rectangular-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 10px;
}

.rectangular-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.card-body {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 0 0 10px 10px;
}

.program-info h3 {
    color: white;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Empty carousel styles */
#emptyCarousel {
    border-radius: 15px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

#emptyCarousel .carousel-control-prev-icon,
#emptyCarousel .carousel-control-next-icon {
    background-color: rgba(0, 97, 242, 0.5);
    border-radius: 50%;
    padding: 15px;
    backdrop-filter: blur(5px);
}

.btn-outline-primary {
    border: 2px solid #00c6f2;
    color: white;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border-radius: 25px;
    padding: 8px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 198, 242, 0.3);
}

.card-title {
    color: #2c3e50;
    font-weight: 600;
}

.alert-info {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    backdrop-filter: blur(10px);
    border-radius: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize carousel with Bootstrap 5
    const carousel = new bootstrap.Carousel(document.getElementById('emptyCarousel'), {
        interval: 3000,
        wrap: true
    });

    // Categories Handlers
    const loadMoreBtn = document.getElementById('loadMoreCategories');
    const showLessBtn = document.getElementById('showLessCategories');
    const kategoriesContainer = document.getElementById('kategoriesContainer');
    let originalCategories = null;
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreBtn.disabled = true;
            loadMoreBtn.querySelector('.button-text').style.display = 'none';
            loadMoreBtn.querySelector('.loading-text').style.display = 'inline-block';
            
            // Store original categories before loading more
            originalCategories = kategoriesContainer.innerHTML;
            
            fetch('<?= Url::to(['site/load-more-categories']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'offset=8&<?= Yii::$app->request->csrfParam ?>=<?= Yii::$app->request->csrfToken ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.categories) {
                    data.categories.forEach(category => {
                        const categoryHtml = `
                            <div class="col-md-3 col-sm-4 col-6 mb-3">
                                <div class="card h-100 kategori-card">
                                    <div class="card-img-container square-container">
                                        <img src="${category.image_url}" 
                                             class="card-img-top" 
                                             alt="${category.nama}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body d-flex flex-column p-2">
                                        <h6 class="card-title text-center mb-0" style="font-size: 0.8rem; line-height: 1.2;">
                                            ${category.nama}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        `;
                        kategoriesContainer.insertAdjacentHTML('beforeend', categoryHtml);
                    });
                    
                    loadMoreBtn.style.display = 'none';
                    showLessBtn.style.display = 'inline-block';
                } else {
                    alert('Gagal memuat kategori tambahan');
                    resetCategoryButtons();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat kategori');
                resetCategoryButtons();
            });
        });
    }

    if (showLessBtn) {
        showLessBtn.addEventListener('click', function() {
            if (originalCategories) {
                kategoriesContainer.innerHTML = originalCategories;
                showLessBtn.style.display = 'none';
                loadMoreBtn.style.display = 'inline-block';
                resetCategoryButtons();
            }
        });
    }

    function resetCategoryButtons() {
        if (loadMoreBtn) {
            loadMoreBtn.disabled = false;
            loadMoreBtn.querySelector('.button-text').style.display = 'inline-block';
            loadMoreBtn.querySelector('.loading-text').style.display = 'none';
        }
    }

    // Brands Handlers
    const loadMoreBrandsBtn = document.getElementById('loadMoreBrands');
    const showLessBrandsBtn = document.getElementById('showLessBrands');
    const brandsContainer = document.getElementById('brandsContainer');
    let originalBrands = null;
    
    if (loadMoreBrandsBtn) {
        loadMoreBrandsBtn.addEventListener('click', function() {
            loadMoreBrandsBtn.disabled = true;
            loadMoreBrandsBtn.querySelector('.button-text').style.display = 'none';
            loadMoreBrandsBtn.querySelector('.loading-text').style.display = 'inline-block';
            
            // Store original brands before loading more
            originalBrands = brandsContainer.innerHTML;
            
            fetch('<?= Url::to(['site/load-more-brands']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'offset=4&<?= Yii::$app->request->csrfParam ?>=<?= Yii::$app->request->csrfToken ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.brands) {
                    data.brands.forEach(brand => {
                        const brandHtml = `
                            <div class="col-md-6 col-sm-6 col-12 mb-3">
                                <div class="card h-100 brand-card">
                                    <div class="card-img-container rectangular-container">
                                        <img src="${brand.image_url}" 
                                             class="card-img-top" 
                                             alt="${brand.nama}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body d-flex flex-column p-2">
                                        <h6 class="card-title text-center mb-0" style="font-size: 0.9rem; line-height: 1.2;">
                                            ${brand.nama}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        `;
                        brandsContainer.insertAdjacentHTML('beforeend', brandHtml);
                    });
                    
                    loadMoreBrandsBtn.style.display = 'none';
                    showLessBrandsBtn.style.display = 'inline-block';
                } else {
                    alert('Gagal memuat brand tambahan');
                    resetBrandButtons();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat brand');
                resetBrandButtons();
            });
        });
    }

    if (showLessBrandsBtn) {
        showLessBrandsBtn.addEventListener('click', function() {
            if (originalBrands) {
                brandsContainer.innerHTML = originalBrands;
                showLessBrandsBtn.style.display = 'none';
                loadMoreBrandsBtn.style.display = 'inline-block';
                resetBrandButtons();
            }
        });
    }

    function resetBrandButtons() {
        if (loadMoreBrandsBtn) {
            loadMoreBrandsBtn.disabled = false;
            loadMoreBrandsBtn.querySelector('.button-text').style.display = 'inline-block';
            loadMoreBrandsBtn.querySelector('.loading-text').style.display = 'none';
        }
    }
});</script>
