<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $userid
 * @property string $name
 * @property string $address
 * @property string $detail
 * @property integer $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','province', 'city', 'county', 'detail','tel'], 'required','message'=>'{attribute}不能为空'],
            [['tel','status'], 'integer'],
            [['name','detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'=>'id',
            'user_id' => '用户id',
            'name' => '收件人',
            'province' => '请选择省份',
            'city' => '请选择市',
            'county' => '请选择地区',
            'detail' => '详细信息',
            'tel' => '手机号',
            'status' => '设为默认地址',
        ];
    }
}
