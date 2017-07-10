<?php
namespace frontend\controllers;
date_default_timezone_set('PRC');

use backend\components\SphinxClient;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsSearchForm;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
     * @inheritdoc
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
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'index';
        $categories = GoodsCategory::find()->where(['=', 'parent_id', '0'])->all();
        return $this->render('index', ['categories' => $categories]);
    }

    public function actionList()
    {
        $this->layout = 'list';
        $search = new GoodsSearchForm();
        $query= Goods::find()->where(['!=', 'status', '-1']);
        if($keyword = \Yii::$app->request->get('keyword')){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            //$cl->SetServer ( '10.6.0.6', 9312);
            //$cl->SetServer ( '10.6.0.22', 9312);
            //$cl->SetServer ( '10.8.8.2', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            // $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');//shopstore_search
            if(isset($res['matches'])){
                $ids = ArrayHelper::map($res['matches'],'id','id');
                $goods = $query->andWhere(['in','id',$ids])->all();
            }else{
                $goods = $query->andWhere(['id'=>0])->all();
            }
        }else{
            $id = \Yii::$app->request->get('cate_id');
            $goods=$query->andwhere(['=', 'goods_category_id', $id])->all();
        }
//        $goods->andWhere(['=','goods_category_id',$id])->all();
//        var_dump($lists);exit;
        if ($goods) {
            return $this->render('list', ['goods' => $goods]);
        } else {
            throw new NotFoundHttpException('该分类没有商品', '404');
        }
    }


    public function actionGoods()
    {
        $this->layout = 'goods';
        $id = $_GET['id'];
        $goods = Goods::findOne(['id' => $id]);
        return $this->render('goods', ['goods' => $goods]);
    }


//    public function actionAdd()
//    {
//        /**
//         * 1.接收post数据
//         * 2.判断用户是否是游客登录，是就将数据存储到cookie
//         *      判断游客
//         *          看购物车里的cookie是否已经有值，有，反序列化=>为后面拼接方便，没有，新空数组保存
//         *      cookie操作
//         *          实例化，request得到只读的cookie对象=》读取cookie=》判断cookie的值是否为空=》response发送cookie数据
//         * 3.不是游客将数据加到数据库=>新建数据库
//         *
//         */
//
//        return $this->redirect(['site/cart']);
//    }


    public function actionCart()
    {
        $this->layout = 'cart';
        /*
         * 1.判断游客登录
         *      将cookie中的数据取出来，装成数组，返回到cart页面
         * 2.会员登录
         *      直接读取数据库数据
         */
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //实例化得到商品
        $goods = Goods::findOne(['id' => $goods_id]);
        //判断是否有该商品
//        if ($goods == null) {
//            throw new NotFoundHttpException('没有该商品', '404');
//        }
        $cookie = \Yii::$app->request->cookies;
        $value = $cookie->get('cart');
        //判断value里是否有值
        if ($value) {
            $cart = unserialize($value);
            //反序列化之后成数组的模型
//                 $cart = ['goods_id'=>'amount','goods_id'=>'amount'...];
        } else {
            $cart = [];
        }
        if (\Yii::$app->user->isGuest) {
            //将本次传来的值放进cookie
            $cookies = \Yii::$app->response->cookies;

            //判断是否购物车是否已经有该商品,只累加数量
            if (key_exists($goods->id, $cart)) {
                if($amount){
                    $cart[$goods_id] += $amount;
                }else{
                unset($cart[$goods_id]);
                }
            }else {
                $cart[$goods_id] = $amount;
            }
            //再把新数据序列化放进cookie
            $new_cookie = new Cookie([
                'name' => 'cart', 'value' => serialize($cart)
            ]);
            $cookies->add($new_cookie);

            //通过cookie里的goods_id找到该商品数据显示到购物车页面
            $model = $cart;
            $models = [];
            foreach ($model as $goods_id => $amount) {
                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
        } else {
            //登录用户
            $member_id = \Yii::$app->user->getId();
            $model = Cart::find()->andWhere(['goods_id'=>$goods_id])->andWhere(['user_id'=>$member_id])->all();
            if($amount==0) {
                $model[0]->delete();
            }else{
                //判断是否有cookie数据
                if (key_exists($goods->id, $cart)) {
                    $amount = $cart[$goods_id] + $amount;
                }
                if($model){
                    foreach ($model as $cart){
                    }
                    $cart->amount += $amount;
                }else{
                    $cart = new Cart();
                    $cart->user_id = $member_id;
                    $cart->goods_id = $goods_id;
                    $cart->amount = $amount;
                }
                $cart->save();
                $member_id = \Yii::$app->user->getId();
                $carts = Cart::find()->where(['user_id'=>$member_id])->all();
                $model=[];
                foreach ($carts as $cart){
                    $model[] = [$cart->goods_id=>$cart->amount];
                }
                foreach ($model as $goods_id => $amount) {
                    $goods = Goods::findOne(['id' => key($amount)])->attributes;
                    $goods['amount'] = $amount[key($amount)];
                    $models[] = $goods;
                }
                $cookie = \Yii::$app->response->cookies;
                $cookie->remove('cart');
            }
        }
        return $this->render('cart', ['models' => $models]);
    }

    public function actionClear(){
        if(\Yii::$app->user->isGuest){
            $this->redirect(['user/login']);
        }
        $this->layout = 'order';
        $member_id = \Yii::$app->user->getId();
        $addresses = Address::find()->where(['user_id'=>$member_id])->all();
        $orders = Cart::find()->where(['user_id'=>$member_id])->all();
//        var_dump($orders);exit;
        return $this->render('order',['orders'=>$orders,'addresses'=>$addresses]);
    }



    public function actionOrder(){
        $model = new Order();
        $member_id = Yii::$app->user->getId();
        $address_id = Yii::$app->request->post('address_id');
        $delivery_id = Yii::$app->request->post('delivery_id');
        $payment_id = Yii::$app->request->post('payment_id');
        $address = Address::findOne(['id'=>$address_id,'user_id'=>$member_id]);
        if($address == null){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->member_id = $member_id;
        $model->name = $address->name;
        $model->province = $address->province;
        $model->city = $address->city;
        $model->county = $address->county;
        $model->address = $address->detail;
        $model->tel = $address->tel;
        $model->delivery_id = $delivery_id;
        $model->delivery_name = Order::$delivery[$delivery_id]['del_name'];
        $model->delivery_price = Order::$delivery[$delivery_id]['del_price'];
        $model->payment_id = $payment_id;
        $model->payment_name = Order::$payment[$payment_id]['pay_name'];
        $model->total = Yii::$app->request->post('total');
        $model->status = 1;
        $model->create_time = time();

        //开启事务
        $transaction = \Yii::$app->db->beginTransaction();

        try{
            $model->save();
            //订单商品详情表
            //根据购物车数据，把商品的详情查询出来，逐条保存
            $carts = Cart::findAll(['user_id'=>Yii::$app->user->id]);
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id' => $cart->goods_id, 'status' => 1]);
                if ($goods == null) {
                    //商品不存在
                    throw new Exception( $goods->name.'商品已售完');
                }
                if ($goods->stock < $cart->amount) {
                    //库存不足
                    throw new Exception( $goods->name.'商品库存不足');
                }
                $order_goods = new OrderGoods();
                $order_goods->order_id = $model->id;
                $order_goods->goods_id = $cart->goods_id;
                $order_goods->goods_name = $goods->name;
                $order_goods->logo = $goods->logo;
                $order_goods->price = $goods->shop_price;
                $order_goods->amount = $cart->amount;
                $order_goods->total = $order_goods->price * $order_goods->amount;
                $order_goods->save(false);
                //扣库存 //扣减该商品库存
                $goods->stock -= $cart->amount;
                $goods->save();
            }
            $transaction->commit();
            //清空购物车
            Cart::deleteAll(['user_id'=>Yii::$app->user->id]);
            return $this->redirect(['site/success']);

        }catch (Exception $exception){
            //回滚
            $transaction->rollBack();
            echo $exception->getMessage();
        }

    }

    public function actionSuccess(){
        $this->layout = 'success';
        return $this->render('success');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
