<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170612_035822_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'good_id' => $this->primaryKey(),
            'content'=>$this->text()->comment('简介')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
