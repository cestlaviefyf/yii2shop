<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //不显示已删除的分类
        $query =  ArticleCategory::find()->where(['!=','status','-1']);
        $count = $query->count();
        $page = new Pagination([
            //三个参数 总条数，每页几条，当前第几页
            'totalCount'=>$count,
            'defaultPageSize'=>3
        ]);
        $models = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }

    public function actionAdd()
    {
        $model = new ArticleCategory();
        $request = new Request();
        if ($request->isPost) {
            //接收表单传来的数据
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionUpdate($id){
        $model = ArticleCategory::findOne($id);
        $request = new Request();
        if ($request->isPost) {
            //接收表单传来的数据
            $model->load($request->post());
            if ($model->validate()) {
                $model->save();
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionDelete($id){
        $model = ArticleCategory::findOne($id);
        $model->status = -1;
        $model->save();
        return $this->redirect(['index']);
    }

}
