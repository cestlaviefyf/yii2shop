<?php
/**
 * 开发需求
 * 1.菜单设置
 * -促销商品（click）
 * -在线商城（view：跳转至商城首页）
 * -个人中心
 * --绑定账户（view）
 * --我的订单（view）
 * --收货地址（view）
 * --修改密码（view）
 * 2.详细功能
 *  点击【促销活动】，发送图文信息（发送5条任意商品信息），点击图文信息中的商品，跳转至商品详情页
 *  点击【在线商城】，跳转至商城首页
 * 点击 【我的订单】，【收货地址】，【修改密码】，判断用户是否绑定账户，如果没有绑定则跳转至绑定账户页面
 * 3.对话功能
 * 用户发送【帮助】，回复以下信息“您可以发送 优惠、解除绑定 等信息”
 * 用户发送【优惠】，效果和点击【促销活动】相同
 * 用户发送【解除绑定】，如用户已绑定账户，则解绑当前openid，并回复解绑成功；否则回复请先绑定账户及绑定页面地址
 */
namespace frontend\controllers;

use backend\models\Goods;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use frontend\models\Member;
use yii\helpers\Url;
use yii\web\Controller;
class WechatController extends Controller
{
    public $enableCsrfValidation = false;
}