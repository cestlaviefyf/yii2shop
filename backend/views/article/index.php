<table class="table table-bordered">
    <tr>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类id</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article):?>
    <tr>
        <td><?=$article->name?></td>
        <td><?=$article->intro?></td>
        <td><?=$article->article_category_id?></td>
        <td><?=$article->sort?></td>
        <td><?=\backend\models\Article::$statusOptions[$article->status]?></td>
        <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['article/update','id'=>$article->id],['class'=>'btn btn-sm btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['class'=>'btn btn-sm btn-danger'])?>
            <?=\yii\bootstrap\Html::a('查看详情',['article-detail/index','article_id'=>$article->id],['class'=>'btn btn-sm btn-success'])?></td>
    </tr>
    <?php endforeach;?>
</table>
<div align="center">
    <?php
    //分页工具条
    echo \yii\widgets\LinkPager::widget([
        'pagination'=>$page
    ]);
    ?>
</div>