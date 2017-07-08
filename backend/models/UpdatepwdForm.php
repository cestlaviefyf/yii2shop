<?php
namespace backend\models;

use yii\base\Model;

class UpdatepwdForm extends Model{

    public $origin_pwd;
    public $new_pwd;
    public $confirm_pwd;

    public function rules()
    {
        return [
            [['origin_pwd','new_pwd','confirm_pwd'],'required'],
            ['confirm_pwd','compare','compareAttribute'=>'new_pwd','message'=>'两次密码不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
          'origin_pwd'=>'原密码',
          'new_pwd'=>'新密码',
          'confirm_pwd'=>'确认新密码'
        ];
    }

    public function Updatepwd($user){
//        var_dump($user->password_hash);exit;
        //先对比输入的旧密码和原密码
        if(\Yii::$app->security->validatePassword($this->origin_pwd,$user->password_hash)){
            //加密新密码，保存
            $user->password_hash = \Yii::$app->security->generatePasswordHash($this->new_pwd);
            $user->save(false);
            return true;
        }else{
            $this->addError('origin_pwd','原密码输入错误');
        }
    }
}