<table class="table table-bordered">
    <?=\yii\bootstrap\Html::a('添加',['rbac/permission-add'],['class'=>'btn btn-info'])?>
    <p></p>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach($permissions as $permission): ?>
    <tr>
        <td><?=$permission->name?></td>
        <td><?=$permission->description?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['rbac/permission-update','name'=>$permission->name],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/permission-delete','name'=>$permission->name],['class'=>'btn btn-danger btn-xs'])?></td>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

