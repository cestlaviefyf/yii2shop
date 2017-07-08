<?php
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;

class MenuWidget extends Widget{

    //widget被调用时实现的代码
    public function run(){
        NavBar::begin([
            'brandLabel' => '小卖部',
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['goods/index']],
        ];
        if (\Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => '登录', 'url' => [\Yii::$app->user->loginUrl]];
        } else {
            $menuItems[] = '<li>'
                . Html::beginForm(['user/logout'], 'post')
                . Html::submitButton(
                    '注销',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
            $menuItems[] = ['label'=>\Yii::$app->user->identity->username,'items'=>[
                ['label'=>'修改个人信息','url'=>['user/updatename?id='.\Yii::$app->user->identity->getId()]],
                ['label'=>'修改密码','url'=>['user/updatepwd?id='.\Yii::$app->user->identity->getId()]]
            ]];
            //根据用户权限显示菜单
            /*$menuItems[] = ['label'=>'用户管理','items'=>[
                ['label'=>'添加用户','url'=>['admin/add']],
                ['label'=>'用户列表','url'=>['admin/index']]
            ]];*/
            //找到所有一级菜单
            $menus = Menu::findAll(['parent_id'=>0]);
            foreach ($menus as $menu) {
                $items = ['label' => $menu->label, 'items' => []];
                foreach ($menu->children as $list) {
                    //根据用户权限判断，该菜单是否显示
                    if (\Yii::$app->user->can($list->url)) {
                        $items['items'][] = ['label' => $list->label, 'url' => [$list->url]];
                    }
                }
                //如果该一级菜单没有子菜单，就不显示
                if (!empty($items['items'])) {
                    $menuItems[] = $items;
                }
            }
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}