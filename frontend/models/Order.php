<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $county
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property double $total
 * @property integer $status
 * @property integer $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    public static $delivery = [
      1=>['del_name'=>'京西快递','del_id'=>1,'del_price'=>10],
      2=>['del_name'=>'顺丰速递','del_id'=>2,'del_price'=>40],
      3=>['del_name'=>'第三方快递','del_id'=>3,'del_price'=>15],
      4=>['del_name'=>'EMS','del_id'=>4,'del_price'=>25]
    ];

    public static $payment = [
        1=>['pay_id'=>1,'pay_name'=>'货到付款','intro'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>['pay_id'=>2,'pay_name'=>'在线支付','intro'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['pay_id'=>3,'pay_name'=>'上门自提','intro'=>'自提时付款，支持现金、POS刷卡、支票支付'],
        4=>['pay_id'=>4,'pay_name'=>'邮局汇款','intro'=>'通过快钱平台收款 汇款后1-3个工作日到账']
        ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['member_id'], 'required'],
//            [['member_id', 'delivery_id', 'payment_id', 'status', 'trade_no', 'create_time'], 'integer'],
//            [['delivery_price', 'total'], 'number'],
//            [['name', 'province', 'city', 'county', 'address', 'delivery_name', 'payment_name'], 'string', 'max' => 255],
//            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'county' => '区',
            'address' => '详细地址',
            'tel' => '手机号',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '状态 0已取消1待付款2待发货3待收货4完成',
            'trade_no' => '第三方支付交易单号',
            'create_time' => '创建时间',
        ];
    }
}
