<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($categories,'name');
echo $form->field($categories,'parent_id')->hiddenInput();
//echo $form->field($categories,'parent_id')->dropDownList($options,['prompt'=>'请选择分类']);
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($categories,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);


\yii\bootstrap\ActiveForm::end();


//使用ztree 静态资源
//    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
//    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>
//    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>

$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',
    ['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($goodcategories);
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
        callback: {
		    onClick: function(event, treeId, treeNode) {
		      //alert(treeNode.id);
		      $('#goodscategory-parent_id').val(treeNode.id);
		    }
	}
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};

        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);
        //修改时要选中当前的父id 必须先获取到
        var node = zTreeObj.getNodeByParam('id', $('#goodscategory-parent_id').val(),null);
        zTreeObj.selectNode(node);
JS
);
$this->registerJs($js);
?>
