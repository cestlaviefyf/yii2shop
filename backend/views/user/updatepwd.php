<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'origin_pwd')->passwordInput();
echo $form->field($model,'new_pwd')->passwordInput();
echo $form->field($model,'confirm_pwd')->passwordInput();
echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();