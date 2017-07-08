<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($user,'username');
echo $form->field($user,'password_hash')->passwordInput();
echo $form->field($user,'repassword')->passwordInput();
echo $form->field($user,'email');
echo $form->field($user,'roles')->checkboxList(\backend\models\User::getRolesOptions());
//echo $form->field($users,'status')->radioList(\backend\models\User::$status);

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();