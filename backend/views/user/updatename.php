<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($users,'username');
echo $form->field($users,'email');
echo $form->field($users,'status')->radioList(\backend\models\User::$status);


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();