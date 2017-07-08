<?php
    if(\Yii::$app->user->can('brand/add')){
        echo \yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-info']);
        }
?>
<p></p>
<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>品牌名</th>
        <th>简介</th>
        <th>logo</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($brands as $brand) : ?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=\yii\bootstrap\Html::img("$brand->logo",['width'=>'70px','height'=>'30px'])?></td>
            <td><?=$brand->sort?></td>
            <td><?=\backend\models\Brand::$statusOptions[$brand->status]?></td>
            <td><?php
                    if(\Yii::$app->user->can('brand/update')){
                echo \yii\bootstrap\Html::a('修改',['brand/update','id'=>$brand->id],['class'=>'btn btn-sm btn-warning']);
                }
                ?>
                <?php
                if(\Yii::$app->user->can('brand/delete')){
                echo \yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['class'=>'btn btn-sm btn-danger']);
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