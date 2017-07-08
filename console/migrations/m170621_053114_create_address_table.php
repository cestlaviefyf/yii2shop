<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_053114_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'userid' => $this->primaryKey(),
            'name'=>$this->string()->notNull()->comment('收件人'),
            'address'=>$this->string()->notNull()->comment('所在地区'),
            'detail'=>$this->string()->notNull()->comment('详细信息'),
            'tel'=>$this->integer(11)->notNull()->comment('手机号'),
            'status'=>$this->integer()->comment('是否默认 0不是 1是')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
