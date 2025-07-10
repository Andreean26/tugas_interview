<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\services\ApiService;
use app\models\Program;
use app\models\Kategori;
use app\models\Best;
use app\models\Brand;
use yii\web\JsonResponse;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'load-more-brands' => ['post'],
                    'load-more-categories' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apiService = new ApiService();
        
        // Get programs data
        $programsData = $apiService->getProgramData();
        $programs = [];
        foreach ($programsData as $programData) {
            $programs[] = Program::fromArray($programData);
        }
        
        // Get hanya 8 kategori pertama untuk initial load
        $kategoriData = $apiService->getKategoriData();
        $categories = [];
        $totalCategories = 0;
        
        if ($kategoriData) {
            $totalCategories = count($kategoriData);
            // Ambil hanya 8 kategori pertama
            $firstEightCategories = array_slice($kategoriData, 0, 8);
            foreach ($firstEightCategories as $categoryData) {
                $categories[] = Kategori::fromArray($categoryData);
            }
        }
        
        // Get best banners data
        $bestData = $apiService->getBestData();
        $bests = [];
        if ($bestData) {
            foreach ($bestData as $bestItem) {
                $bests[] = Best::fromArray($bestItem);
            }
        }

        // Get brands data
        $brandData = $apiService->getBrandData();
        $brands = Brand::fromApiResponse($brandData);

        return $this->render('index', [
            'programs' => $programs,
            'categories' => $categories,
            'totalCategories' => $totalCategories,
            'bests' => $bests,
            'brands' => $brands,
        ]);
    }

    /**
     * Load more categories via AJAX
     */
    public function actionLoadMoreCategories()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $offset = Yii::$app->request->post('offset', 8);
        $apiService = new ApiService();
        $kategoriData = $apiService->getKategoriData();
        
        if ($kategoriData) {
            $remainingCategories = array_slice($kategoriData, $offset);
            $categories = [];
            foreach ($remainingCategories as $categoryData) {
                $category = Kategori::fromArray($categoryData);
                $categories[] = [
                    'nama' => $category->getDisplayName(),
                    'image_url' => $category->image_url,
                ];
            }
            
            return [
                'success' => true,
                'categories' => $categories,
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to load more categories',
        ];
    }

    /**
     * Load more brands via AJAX
     */
    public function actionLoadMoreBrands()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $offset = Yii::$app->request->post('offset', 4);
        $apiService = new ApiService();
        $brandData = $apiService->getBrandData();
        
        if ($brandData) {
            $brands = Brand::fromApiResponse($brandData);
            $remainingBrands = array_slice($brands, $offset);
            
            $brandsArray = [];
            foreach ($remainingBrands as $brand) {
                $brandsArray[] = [
                    'nama' => $brand->getDisplayName(),
                    'image_url' => $brand->image_url,
                ];
            }
            
            return [
                'success' => true,
                'brands' => $brandsArray,
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Failed to load more brands',
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
