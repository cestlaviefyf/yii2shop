<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query =  GoodsCategory::find();
        $count = $query->count();
        $page = new Pagination([
            'totalCount' => $count,
            'defaultPageSize'=>10
        ]);
        $categories = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['categories'=>$categories,'page'=>$page]);
    }

    public function actionTest(){
        //创建一级分类
//        $test = new GoodsCategory();
//        $test->name = '测试';
//        $test->parent_id = 0;
//        $test->makeRoot();
//        var_dump($test);

        //创建二级分类
//        $test2 = new GoodsCategory();
//        $test2->name = '测试';
//        //找到该分类的父id
//        $parent = GoodsCategory::findOne(['id'=>1]);
//        $test2->parent_id = $parent->id;
//        $test2->prependTo($parent);

//        找到所有一级分类节点
//        $roots = GoodsCategory::find()->roots()->all();
//        var_dump($roots);
        //找到所有二级分类节点
//        $parent = GoodsCategory::findOne(['id'=>1]);
//        $leaves = $parent->leaves()->all();
//        var_dump($leaves);
    }

    public function actionZtree(){
        $categories = GoodsCategory::find()->all();
        return $this->renderPartial('ztree',['categories'=>$categories]);//不使用默认模板
    }

    public function actionAdd(){
        $categories = new GoodsCategory();
        if($categories->load(\yii::$app->request->post()) && $categories->validate()){
            //判断父id是否为0，是则创建一级分类节点
            if($categories->parent_id){
                //不为0，prependTo创建
                $parent = GoodsCategory::findOne(['id'=>$categories->parent_id]);
//                var_dump($parent);
            $categories->parent_id = $parent->id;
            $categories->prependTo($parent);
            }else{
                //创建一级节点
                $categories->parent_id = 0 ;
                $categories->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('index');
        }
        //获取所有分类项
//        $categories =ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
        //添加顶级分类
        $goodcategories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['categories'=>$categories,'goodcategories'=>$goodcategories]);

    }

    public function actionUpdate($id){
        $categories = GoodsCategory::findOne(['id'=>$id]);
        if($categories->load(\yii::$app->request->post()) && $categories->validate()){
            //判断父id是否为0，是则创建一级分类节点
            if($categories->parent_id){
                //不为0，prependTo创建
                $parent = GoodsCategory::findOne(['id'=>$categories->parent_id]);
//                var_dump($parent);
                $categories->parent_id = $parent->id;
                $categories->prependTo($parent);
            }else{
                //创建一级节点
                $categories->parent_id = 0 ;
                $categories->makeRoot();
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index');
        }
        //获取所有分类项
//        $categories =ArrayHelper::map(GoodsCategory::find()->asArray()->all(),'id','name');
        //添加顶级分类
        $goodcategories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['categories'=>$categories,'goodcategories'=>$goodcategories]);

    }
}
