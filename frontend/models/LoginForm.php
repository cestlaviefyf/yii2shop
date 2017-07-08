<?php
namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model{

    public $username;
    public $password;
    public $rememberMe;
    public $code;

    public function rules()
    {
        return [
          [['username','password'],'required'],
            ['password','validatePwd'],
            ['rememberMe','boolean'],
//            ['code','captcha']
        ];
    }
    public function attributeLabels()
    {
        return [
          'username'=>'用户名：',
            'password'=>'密码：',
            'rememberMe'=>'记住我',
            'code'=>'验证码：'
        ];
    }

    public function validatePwd(){
        //通过用户名找密码
        $user = Member::findOne(['username'=>$this->username]);
        if($user){
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                $user->last_login_time =time();
                $user->save(false);
                $duration = $this->rememberMe ? 24*3600 : 0;
                \Yii::$app->user->login($user,$duration);
                return true;
            }else{
                $this->addError('password','用户名或密码错误');
            }
        }else{
            $this->addError('username','用户名或密码错误');
        }
        return false;
    }
}