<table class="table table-bordered">
    <?=\yii\bootstrap\Html::a('添加',['rbac/role-add'],['class'=>'btn btn-info'])?>
    <p></p>
    <tr>
        <th>角色名</th>
        <th>描述</th>
        <th width="800px">权限</th>
        <th>操作</th>
    </tr>
    <?php foreach($roles as $role): ?>
    <tr>
        <td><?=$role->name?></td>
        <td><?=$role->description?></td>
        <td><?php foreach (Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
                echo $permission->description;
                echo '&nbsp;&nbsp;&nbsp;';
            }
            ?></td>
        <td><?=\yii\bootstrap\Html::a('修改',['rbac/role-update','name'=>$role->name],['class'=>'btn btn-warning btn-sm'])?>
            <?=\yii\bootstrap\Html::a('删除',['rbac/role-delete','name'=>$role->name],['class'=>'btn btn-danger btn-sm'])?></td>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

