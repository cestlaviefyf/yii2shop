<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $update_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public $smsCode;//短信验证码
    public $code;//验证码
    public $repwd;//确认密码
    public $password;//短信验证码

    const SCENARIO_REGISTER = 'register';

    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username','password','repwd','smsCode','email','tel'],'required','on'=>self::SCENARIO_REGISTER],
            ['code','captcha','on'=>self::SCENARIO_REGISTER],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'update_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            ['repwd','compare','compareAttribute'=>'password','message'=>'两次输入密码不一致'],
            [['tel'], 'string', 'max' => 11],
            ['smsCode','validateSms','message'=>'验证码错误','on'=>self::SCENARIO_REGISTER]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名：',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码：',
            'password' => '密码：',
            'repwd'=>'确认密码：',
            'email' => '邮箱：',
            'tel' => '手机号：',
            'smsCode'=>'短信验证：',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态(-1删除，1正常) ',
            'created_at' => '添加时间',
            'update_at' => '修改时间',
            'code'=>'验证码：'
        ];
    }
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
            $this->update_at = time();
            $this->status = 1;
            $this->last_login_ip = \Yii::$app->request->getUserIP();
            //生成随机字符串
            if( $this->auth_key==null){
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            if($this->password){
                $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
            }
        }
        return parent::beforeSave($insert);
    }

    public function validateSms(){
        //缓存里面没有该电话号码
        $value = Yii::$app->cache->get('tel_'.$this->tel);
        if(!$value || $this->smsCode != $value){
            $this->addError('smsCode','验证码不正确');
            return false;
        }else{
            return true;
        }
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }
}
