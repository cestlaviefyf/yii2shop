<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170625_064949_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->notNull()->comment('用户id'),
            'name'=>$this->string()->notNull()->comment('收货人'),
            'province'=>$this->string()->comment('省'),
            'city'=>$this->string()->comment('市'),
            'county'=>$this->string()->comment('区'),
            'address'=>$this->string()->comment('详细地址'),
            'tel'=>$this->string(11)->comment('手机号'),
            'delivery_id'=>$this->integer()->comment('配送方式id'),
            'delivery_name'=>$this->string()->comment('配送方式'),
            'delivery_price'=>$this->float()->comment('配送方式价格'),
            'payment_id'=>$this->integer()->comment('支付方式id'),
            'payment_name'=>$this->string()->comment('支付方式名称'),
            'total'=>$this->float()->comment('订单金额'),
            'status'=>$this->integer()->comment('状态 0已取消1待付款2待发货3待收货4完成'),
            'trade_no'=>$this->integer()->comment('第三方支付交易单号'),
            'create_time'=>$this->integer()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
