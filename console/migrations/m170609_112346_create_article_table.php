<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170609_112346_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=> $this->string(50)->notNull()->comment('品牌名'),
            'intro'=> $this->text()->comment('简介'),
            'article_category_id'=>$this->integer()->comment('文章文类id'),
            'sort'=>$this->integer()->comment('排序'),
            'status'=>$this->smallInteger(2)->comment('状态'),
            'create_time'=>$this->integer()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
