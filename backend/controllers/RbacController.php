<?php
namespace backend\controllers;

use backend\components\RbacFliter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\web\Controller;

class RbacController extends Controller{

    //使用过滤器
    public function behaviors()
    {
        return [
          'rbac' => [
              'class' => RbacFliter::className(),
          ]
        ];
    }

    //权限首页
    public function actionPermissionIndex(){
        //获取权限
        $permissions = \Yii::$app->authManager->getPermissions();
        return $this->render('permission-index',['permissions'=>$permissions]);
    }


    //添加权限
    public function actionPermissionAdd(){
        //添加权限 实例化
        $model = new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //验证成功之后 添加权限=>permissionForm 处理
            if($model->addPermission()){
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect('permission-index');
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }


    //修改权限
    public function actionPermissionUpdate($name){
        //获取到权限
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        //将获得的权限回显
        $model = new PermissionForm();
        //permission不是活动记录不能直接回显,去表单模型处理
        $model->loadData($permission);
        //判断提交的修改数据
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //到表单模型处理
            if($model->updatePermission($name)){
                //
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect('permission-index');
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }


    //删除权限
    public function actionPermissionDelete($name){
        //获取到权限
        $permission = \Yii::$app->authManager->getPermission($name);
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','权限删除成功');
        return $this->redirect(['permission-index']);
    }


    //角色首页
    public function actionRoleIndex(){
        $roles = \Yii::$app->authManager->getRoles();
        return $this->render('role-index',['roles'=>$roles]);
    }

    //添加角色
    public function actionRoleAdd(){
        //实例化角色表单模型
        $model = new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //到表单模型处理
            if($model->RoleAdd()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect('role-index');
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }


    //修改角色
    public function actionRoleUpdate($name){
        //获得该角色
        $role = \Yii::$app->authManager->getRole($name);
        //回显数据
        $model = new RoleForm();
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //提交给模型处理
            if($model->RoleUpdate($name)){
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }


    public function actionRoleDelete($name){
        $role = \Yii::$app->authManager->getRole($name);
        \Yii::$app->authManager->remove($role);
        return $this->redirect('role-index');
    }
}