<?php
/**
 * @var $this \yii\web\View
 */
use yii\web\JsExpression;

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($models,'name');
echo $form->field($models,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
if($models->logo){
    echo \yii\bootstrap\Html::img('@web'.$models->logo,['id'=>'img_logo','height'=>'50']);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);
}
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将图片展示出来
        $('#img_logo').attr("src",data.fileUrl).show();
        //要将地址写进logo字段
        $('#goods-logo').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo $form->field($models,'goods_category_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($models,'brand_id')->dropDownList(\backend\models\Goods::getBrandOptions(),['prompt'=>'请选择分类']);
echo $form->field($models,'market_price');
echo $form->field($models,'shop_price');
echo $form->field($models,'stock');
echo $form->field($models,'is_on_sale')->radioList(\backend\models\Goods::$is_on_sale);
echo $form->field($models,'status')->radioList(\backend\models\Goods::$status);
echo $form->field($models,'sort');

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();

//使用ztree静态资源
//    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>

$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($options);
$js = new \yii\web\JsExpression(
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
                var setting = {
                    data: {
                        simpleData: {
                            enable: true,
                                idKey: "id",
                                pIdKey: "parent_id",
                                rootPId: 0
                                    }
                                 },
                    callback:{
                        onClick:function(event, treeId, treeNode) {
                          //alert(treeNode.id);
                        $('#goods-goods_category_id').val(treeNode.id);
                        }
                    }
                };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$zNodes};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);
        //回显商品分类
          //修改时要选中当前的父id 必须先获取到
        var node = zTreeObj.getNodeByParam('id', $('#goods-goods_category_id').val(),null);
        zTreeObj.selectNode(node);
JS

);
$this->registerJs($js);
?>
