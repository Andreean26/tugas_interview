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
            <div class="col-3 mb-2">
                <div class="card h-100 kategori-card" style="min-height: 100px;">
                    <div class="card-img-container square-container" style="padding-bottom: 70%;">
                        <?= Html::img($category->image_url, [
                            'class' => 'card-img-top',
                            'alt' => Html::encode($category->nama),
                            'style' => 'width: 100%; height: 100%; object-fit: cover;'
                        ]) ?>
                    </div>
                    <div class="card-body d-flex flex-column p-2">
                        <h6 class="card-title text-center mb-0" style="font-size: 0.75rem; line-height: 1.2;">
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
            <div class="col-6 mb-3">
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
        
        <!-- Brand Carousel -->
        <div id="brandCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                // Group brands into sets of 3 for each slide
                $brandGroups = array_chunk($brands, 2);
                foreach ($brandGroups as $index => $groupBrands):
                ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="d-flex justify-content-center px-4">
                        <?php foreach ($groupBrands as $brand): ?>
                        <div class="mx-2" style="width: 45%;">
                            <div class="card brand-card">
                                <div class="text-center p-3">
                                    <div class="brand-img-container mx-auto mb-2" style="width: 120px; height: 60px;">
                                        <?= Html::img($brand->image_url, [
                                            'alt' => Html::encode($brand->nama),
                                            'style' => 'width: 100%; height: 100%; object-fit: contain;'
                                        ]) ?>
                                    </div>
                                    <h6 class="card-title mb-0" style="font-size: 0.9rem; color: #2c3e50;">
                                        <?= Html::encode($brand->getDisplayName()) ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#brandCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#brandCarousel" data-bs-slide="next">
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
.kategori-card {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    cursor: pointer;
    background: var(--card-gradient);
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin: 0 2px;
}

.brand-card {
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
    padding-bottom: 70%;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
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
#brandCarousel {
    border-radius: 15px;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 10px 0;
    margin: 0 auto;
    max-width: 100%;
}

#brandCarousel .carousel-item {
    padding: 5px 0;
}

#brandCarousel .d-flex {
    margin: 0 auto;
    width: 100%;
    max-width: 800px;
}

#brandCarousel .carousel-control-prev {
    left: 0;
    width: 10%;
    background: linear-gradient(to right, rgba(0,0,0,0.2), transparent);
}

#brandCarousel .carousel-control-next {
    right: 0;
    width: 10%;
    background: linear-gradient(to left, rgba(0,0,0,0.2), transparent);
}

#brandCarousel .brand-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

#brandCarousel .brand-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#brandCarousel .brand-img-container {
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

#brandCarousel .card {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

#brandCarousel .carousel-control-prev-icon,
#brandCarousel .carousel-control-next-icon {
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

/* Responsive styles */
@media (max-width: 768px) {
    #kategoriesContainer .col-3 {
        width: 25% !important;
    }
    
    .kategori-card {
        min-height: 80px !important;
    }
    
    .kategori-card .card-body {
        padding: 0.25rem !important;
    }
    
    .kategori-card .card-title {
        font-size: 0.65rem !important;
    }
    
    .brand-card {
        min-height: 80px !important;
    }
    
    .brand-card .card-body {
        padding: 0.5rem !important;
    }
    
    .brand-card .card-title {
        font-size: 0.8rem !important;
    }
    
    .square-container {
        padding-bottom: 60% !important;
    }
    
    .rectangular-container {
        padding-bottom: 50% !important;
    }
}

/* Brand Carousel Responsive Styles */
@media (max-width: 576px) {
    #brandCarousel .card {
        padding: 0.5rem !important;
    }
    
    #brandCarousel .brand-img-container {
        width: 100px !important;
        height: 50px !important;
    }
    
    #brandCarousel .card-title {
        font-size: 0.75rem !important;
    }
    
    #brandCarousel .mx-2 {
        width: 48% !important;
        margin: 0 1% !important;
    }
    
    #brandCarousel .card .text-center {
        padding: 0.75rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize carousel with Bootstrap 5
    const brandCarousel = new bootstrap.Carousel(document.getElementById('brandCarousel'), {
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
            
            const formData = new FormData();
            formData.append('offset', '8');
            formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');
            
            fetch('<?= Url::to(['site/load-more-categories']) ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.categories) {
                    data.categories.forEach(category => {
                        const categoryHtml = `
                            <div class="col-3 mb-2">
                                <div class="card h-100 kategori-card" style="min-height: 100px;">
                                    <div class="card-img-container square-container" style="padding-bottom: 70%;">
                                        <img src="${category.image_url}" 
                                             class="card-img-top" 
                                             alt="${category.nama}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body d-flex flex-column p-2">
                                        <h6 class="card-title text-center mb-0" style="font-size: 0.75rem; line-height: 1.2;">
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
            
            const brandFormData = new FormData();
            brandFormData.append('offset', '4');
            brandFormData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->csrfToken ?>');
            
            fetch('<?= Url::to(['site/load-more-brands']) ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: brandFormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.brands) {
                    data.brands.forEach(brand => {
                        const brandHtml = `
                            <div class="col-6 mb-3">
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
