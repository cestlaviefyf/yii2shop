<?php
/**
 * Created by PhpStorm.
 * User: Fu Yuefei
 * Date: 2017/6/29
 * Time: 11:37
 */

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\UpdatepwdForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init(); // TODO: Change the autogenerated stub
    }

//    public function actionGetGoodsByBrand(){
//        if($brand_id = \Yii::$app->request->get('id')){
//            $goods = Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
//            return ['status'=>1,'msg'=>'','data'=>$goods];
//        }else{
//            return ['status'=>-1,'msg'=>'没有此分类id'];
//        }
//
//    }

    //用户注册，post方式提交
    public function actionMemberRegister(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $member = new Member();
            $member->username = $request->post('username');
//            $password = \Yii::$app->security->generatePasswordHash($request->post('password'));
            $member->password = $request->post('password');
            $member->email = $request->post('email');
            $member->tel = $request->post('tel');
            if($member->validate()){
                $member->save();
                //要转化为数组，再用json形式返回
                return ['status'=>1,'msg'=>'','data'=>$member->toArray()];
            }
          return ['status'=>-1,'msg'=>'验证失败'];
        }
        return ['status'=>-1,'msg'=>'请用post方式提交'];
    }

    //用户登录
    public function actionMemberLogin(){
        $member = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            if($member->load($request->post()) && $member->validate()){
                if($member->validatePwd()){
                    return ['status'=>1,'msg'=>'','data'=>$member->toArray()];
                };
                return ['status'=>-1,'msg'=>'用户名或密码不正确'];
            }
            return ['status'=>-1,'msg'=>'验证失败'];
        }
        return ['status'=>-1,'msg'=>'请用post方式提交'];
    }

    public function actionMemberUpdatepwd(){
        $member = Member::findOne(['id'=>\Yii::$app->user->getId()]);
        $model = new UpdatepwdForm();
        $request  = \Yii::$app->request;
        if($request->isPost){
            if($model->load($request->post()) && $model->validate()){
                if($model->Updatepwd($member)){
                    return ['status'=>1,'msg'=>'','data'=>$model->toArray()];
                }
                return ['status'=>-1,'msg'=>'原密码不正确'];
            }
            return ['status'=>-1,'msg'=>'验证失败'];
        }
        return ['status'=>-1,'msg'=>'请用post方式提交'];
    }


    public function actionMemberInfo(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'msg'=>'用户未登录'];
        }else{
            $member = Member::findOne(['id'=>\Yii::$app->user->getId()]);
            return ['status'=>1,'msg'=>'','data'=>$member->toArray()];
        }
    }

    //收货地址
    public function actionAddressAdd(){
        if(\Yii::$app->user->isGuest){
            return ['status'=>-1,'msg'=>'用户未登录'];
        }else{
            $model = new Address();
            if($model->load(\Yii::$app->request->post())&& $model->validate()){
                $model->user_id = \Yii::$app->user->getId();
                $model->save();
                return ['status'=>1,'msg'=>'','data'=>$model->toArray()];
            }
            return ['status'=>-1,'msg'=>'验证失败'];
        }
    }


    public function actionAddressUpdate(){
        $id = \Yii::$app->request->get('id');
        $model = Address::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            if($model->load(\Yii::$app->request->post())&& $model->validate()){
                $model->save();
                return ['status'=>1,'msg'=>'','data'=>$model->toArray()];
            }
            return ['status'=>-1,'msg'=>'验证失败'];
        }
        return ['status'=>-1,'msg'=>'请用post方式提交'];
    }

    public function actionAddressDelete(){
        $id = \Yii::$app->request->get('id');
        if($model =Address::findOne(['id'=>$id])){
            $model ->delete();
            return ['status'=>1,'msg'=>'删除成功'];
        };
        return ['status'=>-1,'msg'=>'没有找到此地址'];
    }

    public function actionAddressList(){
        if($models = Address::find()->where(['=','user_id',\Yii::$app->user->getId()])->asArray()->all()){
            return ['status'=>1,'msg'=>'','data'=>$models];
        }
        return ['status'=>-1,'msg'=>'该用户没有地址'];
    }


    //商品分类
    public function actionGetAllGoodsCategory(){
        if($cates = GoodsCategory::find()->asArray()->all()){
            return ['status'=>1,'msg'=>'','data'=>$cates];
        }
        return ['status'=>-1,'msg'=>'没有商品分类'];
    }

    public function actionGetChildren(){
        $id = \Yii::$app->request->get('id');
        if($cate = GoodsCategory::findOne(['id'=>$id])){
            //判断该分类的层级
            if($cate->depth==2){
                return ['status'=>-1,'msg'=>'该分类下没有子分类'];
            }else{
                $cates = GoodsCategory::find()->where(['parent_id'=>$id])->asArray()->all();
                return ['status'=>1,'msg'=>'','data'=>$cates];
            }
        }
        return ['status'=>-1,'msg'=>'没有此分类'];
    }

    public function actionGetParentCate(){
        $id = \Yii::$app->request->get('id');
        if($cate = GoodsCategory::findOne(['id'=>$id])) {
            if ($cate->depth==0){
                return ['status'=>-1,'msg'=>'该分类已是顶级分类'];
            }else{
                $cates = GoodsCategory::find()->where(['id'=>$cate->parent_id])->asArray()->all();
                return ['status'=>1,'msg'=>'','data'=>$cates];
            }
        }
        return ['status'=>-1,'msg'=>'没有此分类'];
    }


    //商品+分页
    public function actionGetGoodsByCate(){
        $cate_id = \Yii::$app->request->get('cate_id');
        if($cate = GoodsCategory::findOne(['id'=>$cate_id])){
            //判断商品所在层级
            switch ($cate->depth){
                case 2:
                    //直接遍历出商品
                    Goods::find()->where(['goods_category_id'=>$cate_id])->all();
                    break;
                case 1://二级分类
                    $ids = ArrayHelper::map($cate->getChildren(),'id','id');

            }
        }
        return ['status'=>-1,'msg'=>'没有此分类'];
    }
}