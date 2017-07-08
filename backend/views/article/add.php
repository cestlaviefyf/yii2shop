<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($article,'name');
echo $form->field($article,'article_category_id')->dropDownList(['1','2'],['prompt'=>'请选择分类']);
echo $form->field($article,'intro')->textarea();
echo $form->field($article,'sort');
echo $form->field($article,'status')->radioList(\backend\models\Article::$statusOptions);

echo $form->field($detail,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
