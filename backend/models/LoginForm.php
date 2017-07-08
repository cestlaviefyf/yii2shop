<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{

    public $username;
    public $password;
    public $last_login_at;
    public $last_login_ip;
    public $rememberMe;

    public function rules(){
        return[
            [['username','password'],'required'],
            ['password','validatePwd'],
            ['rememberMe','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住此次登录'
        ];
    }
    public function validatePwd(){
        //查找出用户，再验证密码
        $user = User::findOne(['username'=>$this->username]);
//        var_dump($user);exit;
        if($user){
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                    //如果成功 就登陆
                $user->last_login_ip = \Yii::$app->request->getUserIP();
                $user->last_login_at =time();
                $user->save(false);
//                $duration = $this->rememberMe ? 24*3600 : 0;
//                var_dump($duration);exit;
                \Yii::$app->user->login($user);
                return true;
                }else{
                    $this->addError('password','密码不正确');
                }
        }else{
            $this->addError('username','用户名不存在');
        }
        return false;
    }

}