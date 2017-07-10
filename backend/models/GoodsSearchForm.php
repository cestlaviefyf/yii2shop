<?php
namespace backend\models;



use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model{
    public $name;
    public $sn;
    public $max_price;
    public $min_price;

    public function rules()
    {
        return [
            ['name','string','max'=>50],
            ['sn','string'],
            ['max_price','double'],
            ['min_price','double']
        ];
    }
    public function search(ActiveQuery $query){
        //先加载get提交过来的数据
        $get = load(\Yii::$app->request->get());
        if($get->name){
            $query->andWhere(['like','name',$get->name]);
        }
        if($get->sn){
            $query->andWhere(['like','sn',$get->sn]);
        }
        if($get->max_price){
            $query->andWhere(['<=','shop_price',$get->max_price]);
        }
        if($get->min_price){
            $query->andWhere(['>=','shop_price',$get->min_price]);
        }
    }
}