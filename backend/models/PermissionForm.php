<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;

    public function rules(){
        return [
            [['name','description'],'required']
        ];
    }

    public function attributeLabels(){
        return [
            'name'=>'名称',
            'description'=>'描述',
        ];
    }

    public function addPermission(){
        $authManager = \Yii::$app->authManager;
        //添加权限
            //验证规则：name不能重复,获取到是否有输入的name的权限
        if($authManager->getPermission($this->name)){
            //有，则存在,提示
            $this->addError('name','该权限已存在');
        }else{
            $permission = $authManager->createPermission($this->name);
            $permission->description = $this->description;
            //保存到数据库
            return $authManager->add($permission);
        }
        return false;
    }

    public function loadData($permission){
        $this->name = $permission->name;
        $this->description = $permission->description;
    }

    public function updatePermission($name){
        //修改的名称不能重复
        $authManager = \Yii::$app->authManager;
//        var_dump($authManager->getPermission($this->name));exit;
        if($name != $this->name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }else{
            $permission = $authManager->getPermission($name);
            $permission->name = $this->name;
            $permission->description = $this->description;
            //保存更新的权限
            return $authManager->update($name,$permission);
        }
        return false;
    }
}