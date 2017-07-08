<?php

namespace backend\controllers;
date_default_timezone_set('PRC');

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Article::find()->where(['!=','status','-1']);
        $count = $query->count();
        $page = new Pagination([
           'totalCount' =>  $count,
            'defaultPageSize' => 2
        ]);
        $articles = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }

    public function actionAdd(){
        $article = new Article();
        $detail = new ArticleDetail();
        $request = new Request();

//        $article->scenario = 'update';
//        $detail->scenario = 'update';

        if ($article->load($request->post()) && $detail->load($request->post())) {
            $isValid = $article->validate();
            $isValid = $detail->validate() && $isValid;
            if ($isValid) {
                $create_time = time();
//                var_dump($create_time);
//                exit;
                $article->create_time= $create_time;
                $article->save(false);
                $detail->article_id = $article->id;
                $detail->save(false);
                return $this->redirect(['index', 'article'=>$article]);
            }
        }

        return $this->render('add', [
            'article' => $article,
            'detail' => $detail,
        ]);
    }
    public function actionUpdate($id){
        $article = Article::findOne($id);
        $detail = ArticleDetail::findOne($id);
        $request = new Request();

//        $article->scenario = 'update';
//        $detail->scenario = 'update';

        if ($article->load($request->post()) && $detail->load($request->post())) {
            $isValid = $article->validate();
            $isValid = $detail->validate() && $isValid;
            if ($isValid) {
                $article->save(false);
                $detail->save(false);
                return $this->redirect(['index', 'article'=>$article]);
            }
        }

        return $this->render('add', [
            'article' => $article,
            'detail' => $detail,
        ]);
    }
    public function actionDelete($id){
        $article = Article::findOne($id);
        $article->status = -1;
        $article->save();
        return $this->redirect(['index']);
    }
}
