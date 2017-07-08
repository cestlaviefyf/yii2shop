
<div class="address_bd mt10">
    <h4>新增收货地址</h4>
    <?php
    $form = \yii\widgets\ActiveForm::begin(
        [
            'fieldConfig'=>[
                'options'=>[
                    'tag'=>'li',
                ],
                'errorOptions'=>[
                    'tag'=>'p'
                ],
            ]]
    );
    ?>
    <ul>
        <?=$form->field($model,'name')->textInput(['class'=>'txt']);?>
        <?=$form->field($model,'tel')->textInput(['class'=>'txt']);?>
        <li><label for="">所在地区：</label>
            <?=$form->field($model,'province',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'请选择']);?>
            <?=$form->field($model,'city',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'请选择']);?>
            <?=$form->field($model,'county',['template' => "{input}",'options'=>['tag'=>false]])->dropDownList([''=>'请选择']);?>
        </li>
        <?=$form->field($model,'detail')->textInput(['class'=>'txt']);?>
        <?=$form->field($model,'status')->checkbox(['class'=>'check']);?>
        <li>
            <label for="">&nbsp;</label>
            <input type="submit" name="" class="btn" value="保存" />
        </li>
    </ul>

    <?php \yii\widgets\ActiveForm::end();?>
</div>

<!-- 右侧内容区域 end -->
<?php

/**
 * @var $this \yii\web\View
 *
 */
//首先引入address文件
$this->registerJsFile('@web/js/address.js');
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
            //先将省取出来
            $(address).each(function() {
              var province = '<option value='+this.name+'>'+this.name+'</option>';
              //将省放进select
              $("#address-province").append(province);
            })
            
            //省发生改变时，遍历该省下面的市
            $("#address-province").change(function() {
                //读出选中的省=>$(this)
                $('#address-city').find('option:gt(0)').remove();
                $('#address-county').find('option:gt(0)').remove();
                var province = $(this).val();
               
              $(address).each(function() {
                if(this.name==province){
                    //遍历市级
                    $(this.city).each(function() {
                       var city = '<option value='+this.name+'>'+this.name+'</option>';
                        $("#address-city").append(city);
                    })
                }
              })
            })
            //当市发生变化时
            $('#address-city').change(function() {
              //先清除三级菜单
              $('#address-county').find('option:gt(0)').remove();
              //获取到选中的二级菜单
              var city = $(this).val();
              //遍历第三级菜单
              $(address).each(function() {
                if(this.name == $('#address-province').val()){
                    $(this.city).each(function() {
                      if(this.name == city){
                          $(this.area).each(function(i,v) {
                             var county = '<option value="'+v+'">'+v+'</option>'; 
                             $("#address-county").append(county);
                          })
                      }
                    })
                }
              })

            })
JS

));

$js = '';
if($model->province){
    $js .= '$("#address-province").val("'.$model->province.'");';
}
if($model->city){
    $js .= '$("#address-province").change();$("#address-city").val("'.$model->city.'");';
}
if($model->county){
    $js .= '$("#address-city").change();$("#address-county").val("'.$model->county.'");';
}
$this->registerJs($js);
