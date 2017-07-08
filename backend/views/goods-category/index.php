<?=\yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-info'])?>
<p></p>

<table class="table table-bordered">
    <tr>
        <th>分类名</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($categories as $category): ?>
        <tr>
            <td><?=$category->name?></td>
            <td><?=$category->intro?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['goods-category/update','id'=>$category->id],['class'=>'btn btn-sm btn-info'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$category->id],['class'=>'btn btn-sm btn-danger'])?></td>

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