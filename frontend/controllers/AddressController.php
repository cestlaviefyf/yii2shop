<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{

    public $layout = 'address';

    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login']);
        }else{
            $member_id = \Yii::$app->user->getId();
            $addresses = Address::find()->andWhere(['!=','status','-1'])->andWhere(['=','user_id',$member_id])->all();
            $models = new Address();
            if($models->load(\Yii::$app->request->post()) && $models->validate()){
//           var_dump($models);exit;
                $models->user_id = $member_id;
                $models->save();
                return $this->redirect(['address/index']);
            }
            return $this->render('index',['models'=>$models,'addresses'=>$addresses]);
        }

    }

    public function actionUpdate($id){
        $model = Address::findOne($id);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->redirect(['address/index']);
        }
        return $this->render('update',['model'=>$model]);
    }

    public function actionDelete($id){
        $model = Address::findOne($id);
        var_dump($model);
        $model->status = -1;
        $model->save();
        return $this->redirect(['address/index']);
    }

    public function actionDefault($id){
        $model = Address::findOne(['id'=>$id]);
        //如果点击取消
        if($model->status == 1){
            $model->status = 0;
            $model->save();
            return $this->redirect(['address/index']);
        }else{
            //点击设置默认，先找是否有默认设置，有先清0
            if(Address::findOne(['status'=>1])){
                $default_model = Address::findOne(['status'=>1]);
                $default_model-> status = 0 ;
                $default_model->save();
            }
            $model->status = 1;
            $model->save();
            return $this->redirect(['address/index']);
        }
    }
}
