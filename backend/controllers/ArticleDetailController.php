<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;

class ArticleDetailController extends \yii\web\Controller
{
    public function actionIndex($article_id)
    {
        $detail = ArticleDetail::findOne($article_id);
        $article = Article::findOne($article_id);
//        var_dump($article);
//        exit;
        return $this->render('index',['detail'=>$detail,'article'=>$article]);
    }

}
