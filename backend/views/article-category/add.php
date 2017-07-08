<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textinput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textinput();
echo $form->field($model,'status')->radioList(\backend\models\Articlecategory::$statusOptions);
echo $form->field($model,'is_help')->textInput();

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();