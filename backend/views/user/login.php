<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($users,'username');
echo $form->field($users,'password')->passwordInput();
echo $form->field($users,'rememberMe')->checkbox();
echo yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
