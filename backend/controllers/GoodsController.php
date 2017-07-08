<?php

namespace backend\controllers;
date_default_timezone_set('PRC');

use backend\components\RbacFliter;
use backend\models\Gallery;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use xj\uploadify\UploadAction;
use yii\web\NotFoundHttpException;

class GoodsController extends \yii\web\Controller
{

    //使用过滤器
//    public function behaviors()
//    {
//        return [
//            'rbac'=>[
//                'class'=>RbacFliter::className(),
//            ]
//        ];
//    }

    public function actionIndex()
    {
        $query =Goods::find()->where(['!=','status','-1']);
        $count = $query->count();
        $page = new Pagination([
            'totalCount' => $count,
            'defaultPageSize'=>3
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }

    public function actionAdd(){
        $models = new Goods();
        if($models->load(\Yii::$app->request->post()) && $models->validate()){
            $create_day=date('Ymd', time());
            $day = GoodsDayCount::find()->where(['day'=>$create_day])->all();
            if($day){
                $count = GoodsDayCount::findOne(['day'=>$create_day]);
                $count->count=$count->count + 1 ;
//                var_dump($count->count);exit;
                $count->save();
            }else{
                $Daycount = new GoodsDayCount();
                $Daycount->day=$create_day;
                $Daycount->save();
                $count = GoodsDayCount::findOne(['day'=>$create_day]);
                $count->count =$count->count +1 ;
                $count->save();
            }
            $length = 5 - strlen($count->count);
            $sn = $create_day.str_repeat('0',$length).$count->count;
            $models->create_time=time();
            $models->sn=$sn;
//            var_dump($models);exit;
            $models->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('index');
        }
        //获取分类项
//        $options = ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
        $options = ArrayHelper::merge([['name'=>'顶级分类','id'=>0,'parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['models'=>$models,'options'=>$options]);
    }

    public function actionUpdate($id){
        $models = Goods::findOne(['id'=>$id]);
        if($models->load(\Yii::$app->request->post()) && $models->validate()){
//            var_dump($models);exit;
            $models->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('index');
        }
        //获取分类项
//        $options = ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
        $options = ArrayHelper::merge([['name'=>'顶级分类','id'=>0,'parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['models'=>$models,'options'=>$options]);
    }

    public function actionDelete($id){
        $models = Goods::findOne(['id'=>$id]);
        $models->status = -1;
        $models->save();
        return $this->redirect('index');
    }


    //相册
    public function actionGallery($id){
        $goods = Goods::findOne(['id'=>$id]);
        if($goods){
            return $this->render('gallery',['goods'=>$goods]);
        }else{
            throw new NotFoundHttpException('没有该商品','404');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $gallery = new Gallery();
                    $gallery->goods_id = \Yii::$app->request->post('goods_id');
                    $gallery->path = $action->getWebUrl();
                    $gallery->save();
                    $action->output['fileUrl'] = $gallery->path;
                    $action->output['id'] = $gallery->goods_id;
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}
