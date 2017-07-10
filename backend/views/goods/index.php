<?php
if(\Yii::$app->user->can('goods/add')){
    echo \yii\bootstrap\Html::a('添加',['goods/add'],['class'=>'btn btn-info']);
}
?>
<p></p>

<?php
$form = \yii\bootstrap\ActiveForm::begin([
        'method'=>'get',
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'options'=>['class'=>'form-inline']
]);
echo $form->field($search,'name')->textInput(['placeholder'=>'商品名','name'=>'keyword'])->label(false);
echo '&nbsp;';
echo $form->field($search,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo '&nbsp;';
echo $form->field($search,'min_price')->textInput(['placeholder'=>'价格下限'])->label(false);
echo '&nbsp;';
echo $form->field($search,'max_price')->textInput(['placeholder'=>'价格上限'])->label('-');
echo '&nbsp;';
echo \yii\bootstrap\Html::submitButton('搜索');
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered">
    <tr>
        <th>商品名称</th>
        <th>货号</th>
        <th>logo</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model): ?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><?=\yii\bootstrap\Html::img($model->logo,['width'=>'70px','height'=>'30px'])?></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=\backend\models\Goods::$is_on_sale[$model->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$status[$model->status]?></td>
            <td><?=$model->sort?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
            <td><?php
                if(\Yii::$app->user->can('goods/update')){
                    echo \yii\bootstrap\Html::a('修改',['goods/update','id'=>$model->id],['class'=>'btn btn-sm btn-warning']);
                }
                ?>
                <?=\yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$model->id],['class'=>'btn btn-sm btn-success'])?>
                <?php
                if(\Yii::$app->user->can('goods/delete')){
                    echo \yii\bootstrap\Html::a('删除',['goods/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger']);
                }
                ?>
                </tr>
    <?php endforeach; ?>
</table>
<div align="center">
    <?php
    //分页工具条
    echo \yii\widgets\LinkPager::widget([
        'pagination'=>$page
    ]);
    ?>
</div>