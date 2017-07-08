<?php
namespace backend\controllers;
date_default_timezone_set('PRC');

use backend\components\RbacFliter;
use backend\models\LoginForm;
use backend\models\UpdatepwdForm;
use backend\models\User;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class UserController extends \yii\web\Controller
{
//    //使用过滤器
//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFliter::className(),
//            ]
//        ];
//    }


    public function actionAdd(){
        $user = new User();
        if($user->load(\Yii::$app->request->post()) && $user->validate()){
//            var_dump($users);exit;
            $user->password_hash = \Yii::$app->security->generatePasswordHash($user->password_hash);
            $create_time = time();
            $user->created_at = $create_time;
            $user->save(false);

            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('index');
        }
        return $this->render('add',['user'=>$user]);

    }

    public function actionUpdate($id){
        $users = User::findOne(['id'=>$id]);
        $users->roles = ArrayHelper::getColumn(\Yii::$app->authManager->getRolesByUser($id),'name');
        if($users->load(\Yii::$app->request->post()) && $users->validate()){
            $update_time = time();
            $users->updated_at = $update_time;
            $users->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index');
        }
        return $this->render('update',['users'=>$users]);
    }

    public function actionUpdatename($id){
        $users = User::findOne(['id'=>$id]);
        if($users->load(\Yii::$app->request->post()) && $users->validate()){
            $update_time = time();
            $users->updated_at = $update_time;
            $users->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index');
        }
        return $this->render('updatename',['users'=>$users]);
    }


    public function actionUpdatepwd($id){
        $user = User::findOne(['id'=>$id]);
        $model = new UpdatepwdForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
           if($model->Updatepwd($user)){
               \Yii::$app->session->setFlash('success','密码修改成功');
               return $this->redirect('index');
           }
        }
        return $this->render('updatepwd',['model'=>$model]);
    }
    public function actionDelete($id){
        $users = User::findOne(['id'=>$id]);
        $users->status = -1;
        $users->save();

        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('index');
    }

    public function actionLogin(){
        $users = new LoginForm();
        if($users->load(\Yii::$app->request->post()) && $users->validate()){
//            var_dump($users->validate());exit;
//          var_dump($users);exit;
            if($users->validatePwd()){
                \Yii::$app->session->setFlash('success','登录成功');
//              var_dump(\Yii::$app->user->identity);exit;
                return $this->redirect('index');
            }
        }
        return $this->render('login',['users'=>$users]);
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    public function actionIndex()
    {
        $user = \Yii::$app->user->isGuest;
//        var_dump($user);
        if($user){
            return $this->redirect('login');
        }else{
             $users = User::find()->where(['!=','status','-1'])->all();
            return $this->render('index',['users'=>$users]);
        }

    }

    public function actionResetpwd($id){
        $model = User::findOne(['id'=>$id]);
        $model->password_hash = \Yii::$app->security->generatePasswordHash(12345678);
        $model->save();
        \Yii::$app->session->setFlash('success','重置成功');
        return $this->redirect('index');
    }

//    public function actionA()
//    {
//        var_dump(session_id());
//        $model = User::find()->one();
//        \Yii::$app->user->login($model);
//        var_dump(session_id());
//        var_dump($_SESSION);
//
//    }
//    public function actionB()
//    {
////        var_dump($_SESSION);
//        var_dump(session_id());
//    }
}
