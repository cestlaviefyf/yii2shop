<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170624_092119_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer()->notNull()->comment('用户id'),
            'goods_id'=>$this->integer()->comment('商品'),
            'amount'=>$this->integer()->comment('数量')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
