<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Product;
use app\models\Cart;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','login','error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'order','products','cart', 'add', 'remove', 'delete', 'edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
        return $this->render('index');
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
     * Displays products page.
     *
     * @return string
     */
    public function actionProducts()
    {
        $provider = new ActiveDataProvider([
            'query' => Product::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        
        return $this->render('products', [
            'provider' => $provider
        ]);
    }

    /**
     * Adds product to cart.
     * 
     * @param int $id index of the product
     * @return string
     */
    public function actionAdd($id)
    {
        Cart::addToCart($id);
        return $this->redirect(['products']);
    }

    /**
     * Displays shopping cart page.
     *
     * @return string
     */
    public function actionCart()
    {
        $provider = new SqlDataProvider([
            'sql' => 'SELECT cart.id, cart.comment, cart.quantity, product.name, product.description, product.price, product.tax
            FROM cart
            INNER JOIN product ON cart.product_id = product.id',
        ]);
        $total = Cart::getTotal();
        return $this->render('cart', [
            'provider' => $provider,
            'total' => $total
        ]);
    }

    /**
     * Removes single product item from cart.
     * 
     * @param int $id index of the product item in cart
     * @return string
     */
    public function actionRemove($id)
    {
        Cart::removeFromCart($id);
        return $this->redirect(['cart']);
    }

    /**
     * Deletes selected product items from cart.
     * 
     * @param int $id index of the product item in cart
     * @return string
     */
    public function actionDelete($id)
    {
        Cart::deleteFromCart($id);
        return $this->redirect(['cart']);
    }
    
    /**
     * Edit comment of selected product items in cart.
     * 
     * @param int $id index of the product item in cart
     * @return string
     */
    public function actionEdit($id)
    {
        $model = Cart::find()->where(['id' => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('Comment is submitted');
            return $this->redirect(['cart']);
        }
        return $this->renderAjax('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Empties the cart.
     *
     * @return string
     */
    public function actionOrder()
    {
        Cart::deleteAllFromCart();
        return $this->render('order');
    }
}
