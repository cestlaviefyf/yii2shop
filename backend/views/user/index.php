<?php
if(\Yii::$app->user->can('user/add')){
    echo \yii\bootstrap\Html::a('添加',['user/add'],['class'=>'btn btn-info']);
}
?>
<p></p>
<table class="table table-bordered">
    <tr>
        <th>用户名</th>
        <th>email</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>上次修改时间</th>
        <th>上次登录ip</th>
        <th>用户角色</th>
        <th>上次登录时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($users as $user): ?>
        <tr>
            <td><?=$user->username?></td>
            <td><?=$user->email?></td>
            <td><?=\backend\models\User::$status[$user->status]?></td>
            <td><?=date('Y-m-d H:i:s',$user->created_at)?></td>
            <td><?=date('Y-m-d H:i:s',$user->updated_at)?></td>
            <td><?=$user->last_login_ip?></td>
            <td><?php foreach (Yii::$app->authManager->getRolesByUser($user->id) as $role){
                    echo $role->description;
                    echo '&nbsp;&nbsp;&nbsp;';
                }
                ?></td>
            <td><?=date('Y-m-d H:i:s',$user->last_login_at)?></td>
            <td><?php
                if(\Yii::$app->user->can('user/update')){
                    echo \yii\bootstrap\Html::a('修改信息',['user/update','id'=>$user->id],['class'=>'btn btn-sm btn-warning']);
                }
                ?>
                <?php
                if(\Yii::$app->user->can('user/delete')){
                    echo \yii\bootstrap\Html::a('删除',['user/delete','id'=>$user->id],['class'=>'btn btn-sm btn-danger']);
                }
                ?>
                <?php
                if(\Yii::$app->user->can('user/resetpwd')){
                echo \yii\bootstrap\Html::a('重置密码',['user/resetpwd','id'=>$user->id],['class'=>'btn btn-sm btn-success']);
                }
                ?>
                </tr>
    <?php endforeach; ?>
</table>