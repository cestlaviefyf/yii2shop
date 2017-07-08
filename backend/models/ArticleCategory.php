<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "Article_Category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 * @property integer $is_help
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    static public $statusOptions=[-1=>'删除',0=>'隐藏',1=>'正常'];
    static public $is_help_options = [1=>'帮助',0=>'快讯'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Article_Category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status', 'is_help'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique','message'=>'分类已存在'],//分类名称不能重复
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '类型',
        ];
    }
}
