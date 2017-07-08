<?php

use yii\db\Migration;

/**
 * Handles the creation of table `manu`.
 */
class m170619_054346_create_manu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('manu', [
            'id' => $this->primaryKey(),
            'label'=>$this->string(20)->notNull()->comment('名称'),
            'url'=>$this->string(200)->comment('路由地址'),
            'parent_id'=>$this->integer()->comment('上级菜单'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('manu');
    }
}
