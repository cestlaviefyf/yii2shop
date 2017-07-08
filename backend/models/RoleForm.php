<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];


    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe']

        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'描述',
            'permissions'=>'权限'
        ];
    }


    static function getPermissionOptions(){
        //要将所有权限用数组显示出来
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }


    public function RoleAdd(){
        $authManager = \Yii::$app->authManager;
        //验证是否存在该用户名
        if($authManager->getRole($this->name)){
            $this->addError('name','该用户角色已存在');
        }else{
            //不存在就保存角色
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
//            var_dump($this);exit;
            //添加到数据表
            if($authManager->add($role)){
                //关联权限
                foreach ($this->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    //关联成功，添加=>addchild
                    if($permission) $authManager->addChild($role,$permission);
//                        var_dump($authManager->addChild($role,$permission));exit
                }
                return true;
            }

        }
        return false;
    }

    public function loadData($role){
//        var_dump($role);exit;
        $this->name = $role->name;
        $this->description = $role->description;
        //获取该角色权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
//        var_dump($permissions);exit;
        foreach($permissions as $permission){
            //要将权限放在$permissions[]中,且是把name放进去
            $this->permissions[]= $permission->name;
        }
    }

    public function RoleUpdate($name){
        $authManager = \Yii::$app->authManager;
        //获得该role
        $role = $authManager->getRole($name);
        $role->name = $this->name;
        $role->description = $this->description;
        //判断角色名是否重复
        if($name!=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');
        }else{
            //清空原权限
            $authManager->removeChildren($role);
            //赋值权限
            foreach ($this->permissions as $permissionName){
                $permission = $authManager->getPermission($permissionName);
                if($permission) $authManager->addChild($role,$permission);
            }
            return true;
        }
        return false;
    }
}