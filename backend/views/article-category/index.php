<?=\yii\bootstrap\Html::a('添加',['article-category/add'],['class'=>'btn btn-info'])?>
<p></p>
<table class="table table-bordered">
    <tr>
        <th>分类名</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model): ?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?=\backend\models\ArticleCategory::$statusOptions[$model->status]?></td>
        <td><?=$model->is_help?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['article-category/update','id'=>$model->id],['class'=>'btn btn-sm btn-warning']) ?>
            <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$model->id],['class'=>'btn btn-sm btn-danger']) ?></td>
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