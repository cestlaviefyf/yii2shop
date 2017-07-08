<?php

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
class UserController extends \yii\web\Controller
{
    public $layout = 'login';

    public function actionRegister(){
        $model = new Member(['scenario'=>Member::SCENARIO_REGISTER]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save(false);
            return $this->redirect(['index/index']);
        }
        return $this->render('register',['model'=>$model]);
    }


    public function actionLogin(){
        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
            if($model->validatePwd()){
                return $this->redirect(['site/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }


    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->redirect(['login']);
    }

    //发送短信验证
    public function actionSendmsg(){
        //接收发送过来的电话号码
        $tel = \Yii::$app->request->post('tel');
        $code = rand(1000,9999);
        $result = self::actionSms($tel,$code);
        if($result){
            //保存tel和code
            \Yii::$app->cache->set('tel_'.$tel,$code,15*60);
            echo 'success';
        }else{
            echo 'fail';
        }
    }


    public function actionSms($tel,$code){
// 配置信息
        $config = [
            'app_key'    => '24495269',
            'app_secret' => '233c0fabace382714b0db318277bae67',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
// 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum($tel)
            ->setSmsParam([
                'name'=>'老哥',
                'code' => $code,
            ])
            ->setSmsFreeSignName('付越飞')
            ->setSmsTemplateCode('SMS_71570158');

        $resp = $client->execute($req);
        return true;
    }

}
