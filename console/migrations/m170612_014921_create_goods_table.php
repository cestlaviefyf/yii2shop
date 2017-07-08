<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170612_014921_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('商品名称'),
            'sn'=>$this->string()->notNull()->comment('货号'),
            'logo'=>$this->string(255)->comment('logo图片'),
            'goods_category_id'=>$this->integer()->notNull()->comment('商品分类id'),
            'brand_id'=>$this->integer()->notNull()->comment('品牌分类'),
            'market_price'=>$this->decimal()->notNull()->comment('市场价格'),
            'shop_price'=>$this->decimal()->notNull()->comment('商品价格'),
            'stock'=>$this->integer()->comment('库存'),
            'is_on_sale'=>$this->smallInteger()->comment('是否在售，1在售 0下架'),
            'status'=>$this->smallInteger()->comment('状态 1正常 0回收站'),
            'sort'=>$this->integer()->comment('排序'),
            'create_time'=>$this->integer()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
