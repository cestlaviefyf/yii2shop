<?php

namespace backend\controllers;

use backend\components\RbacFliter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;

class BrandController extends \yii\web\Controller
{

    //使用过滤器
//    public function behaviors()
//    {
//        return [
//          'rbac'=>[
//              'class'=>RbacFliter::className(),
//          ]
//        ];
//    }

    public function actionIndex()
    {
        $query = Brand::find()->where(['!=','status','-1']);
        $count = $query->count();
        $page = new Pagination([
            'totalCount' => $count,
            'defaultPageSize'=>3
        ]);
        $brands = $query->offset($page->offset)->limit($page->limit)->all();
//        var_dump($brands);
//        exit;
        return $this->render('index',['brands'=>$brands,'page'=>$page]);
    }

    public function actionAdd(){
        $brand = new Brand();
        $request = new Request();
        if($request->isPost){
            $brand ->load($request->post());
            //实例化上传图片
//            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
            if($brand->validate()){
                //保存图片名
//                var_dump($brand->imgFile);
//                exit;
//                $img_name = uniqid().'.'.$brand->imgFile->getExtension();
//                var_dump($img_name);
//                exit;
                //保存图片
//                $brand->imgFile->saveAs(\Yii::getAlias('@webroot').'/images/brands/'.$img_name,false);
                $brand->save();
            }
//            $brand->logo = '/images/brands/'.$img_name;

            return $this->redirect('index');
        }
        return $this->render('add',['brand'=>$brand]);
    }

    public function actionUpdate($id){
        $brand = Brand::findOne($id);
//        var_dump($brand);
//        exit;
        $request = new Request();
        if($request->isPost){
            $brand ->load($request->post());
            //实例化上传图片
//            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
            if($brand->validate()){
                //保存图片名
//                var_dump($brand->imgFile);
//                exit;
//                $img_name = uniqid().'.'.$brand->imgFile->getExtension();
//                var_dump($img_name);
//                exit;
                //保存图片
//                $brand->imgFile->saveAs(\Yii::getAlias('@webroot').'/images/brands/'.$img_name,false);
                $brand->save();
            }
//            $brand->logo = '/images/brands/'.$img_name;
            $brand->save();
            return $this->redirect('index');
        }
        return $this->render('add',['brand'=>$brand]);
    }

    public function actionDelete($id){
        $brand = Brand::findOne($id);
//        var_dump($brand->status);
//        exit;
        $brand->status = -1;
        $brand->save();
        return $this->redirect(['index']);
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
                    'extensions' => ['jpg', 'png','bmp'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl = $action->getWebUrl();
                    //调用七牛云组件，将图片上传
                    $qiniu = \Yii::$app->qiniu;
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取该图片的地址
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl']=$url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    public function actionTest(){
        $ak = 'W2IswhjFT1uu-GUzUGxTEazWk08CThbrQwEBfGwZ';
        $sk = 'CT-yMAAQh9FTAOctGW1CHamymsC0_GJ5SvrLE9ZF';
        $domain = 'or9r8j3lc.bkt.clouddn.com';
        $bucket = 'cestlavie';

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        //要上传的文件
        $filename = \Yii::getAlias('@webroot').'/upload/apple.jpg';
        $key = 'apple.jpg';
        $re = $qiniu->uploadFile($filename,$key);
//        var_dump($re);exit;
        $url = $qiniu->getLink($key);
        var_dump($url);
    }

}
