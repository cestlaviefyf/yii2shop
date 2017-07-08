<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gallery`.
 */
class m170623_054928_create_gallery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('gallery', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->notNull(),
            'path'=>$this->string()
            ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('gallery');
    }
}
