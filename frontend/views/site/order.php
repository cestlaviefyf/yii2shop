<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <div class="fillin_bd">
        <?=\yii\helpers\Html::beginForm(['site/order'],'post')?>
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <h5><?=\yii\helpers\Html::a('地址管理',['address/index'])?></h5>
            <br>
            <div class="address_info">
                <?php foreach ($addresses as $address):?>
                <p>
                   <input type="radio" name="address_id" value="<?=$address->id?>" <?=($address->status==1) ? 'checked="checked"' : '' ?>"/><?=$address->name.' '.$address->tel.' '.$address->province.' '.$address->city.' '.$address->county.' '.$address->detail?>
                </p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $deliveries=\frontend\models\Order::$delivery;
                    $delivery_price = 0;
                    foreach ($deliveries as $key=>$delivery):?>

                        <tr class="cur_del">

                            <td><input type="radio" name="delivery_id" class="del" <?=$key==1?'checked="checked"' :''?> value="<?=$delivery['del_id']?>"/><?=$delivery['del_name']?>
                            </td>
                            <td class="d_price">￥<span><?=$delivery['del_price']?></span></td>
                            <td>每张订单不满499.00元,运费15.00元, 订单4...</td>
                        </tr>
                        <?php
                        $delivery_price = $delivery['del_price'];
                        ?>
                    <?php endforeach;?>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php $payment=\frontend\models\Order::$payment;
                    foreach ($payment as $key=>$pay):
                        ?>
                        <tr class="cur_pay">
                            <td class="col1"><input type="radio" name="payment_id" <?=$key==1?'checked="checked"' :''?> value="<?=$pay['pay_id']?>" /><?=$pay['pay_name']?></td>
                            <td class="col2"><?=$pay['intro']?></td>
                        </tr>
                    <?php endforeach;?>

                </table>
            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
<!--        <div class="receipt none">-->
<!--            <h3>发票信息 </h3>-->
<!---->
<!--            <div class="receipt_select ">-->
<!---->
<!--                    <ul>-->
<!--                        <li>-->
<!--                            <label for="">发票抬头：</label>-->
<!--                            <input type="radio" name="type" checked="checked" class="personal" />个人-->
<!--                            <input type="radio" name="type" class="company"/>单位-->
<!--                            <input type="text" class="txt company_input" disabled="disabled" />-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <label for="">发票内容：</label>-->
<!--                            <input type="radio" name="content" checked="checked" />明细-->
<!--                            <input type="radio" name="content" />办公用品-->
<!--                            <input type="radio" name="content" />体育休闲-->
<!--                            <input type="radio" name="content" />耗材-->
<!--                        </li>-->
<!--                    </ul>-->
<!--            </div>-->
<!--        </div>-->
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                $total = 0;
                ?>
                <?php foreach ($orders as $order):?>
                <tr>
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com'.$order->goods->logo)?></a>  <strong><a href=""><?=$order->goods->name?></a></strong></td>
                    <td class="col3"><?=$order->goods->shop_price?></td>
                    <td class="col4"><?=$order->amount?></td>
                    <td class="col5"><span><?=$order->amount * $order->goods->shop_price?></span></td>
                    <?php
                    $count = $count+1;
                    $total += $order->amount * $order->goods->shop_price;
                    ?>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$count?>件商品，总商品金额：</span>
                                <em id="total"><?='￥'.$total?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>￥</em>
                            </li>
                            <li>
                                <span >运费：</span>
                                <em id="del_price"></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="final"><?=$total?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <input type="hidden" name="total" value="<?=$total+$delivery_price?>">
        <p><?=\yii\helpers\Html::submitButton('提交')?></p>
        <?=\yii\helpers\Html::endForm(); ?>
        <p>应付总额：<strong class="final">￥</strong></p>
    </div>
</div>

<?php
/**
 * @var $this \yii\web\View
 */

$this->registerJs(new \yii\web\JsExpression(
        <<<JS
            var tr = $('.cur_del :checked').closest('tr');
            var price = tr.find('td:eq(1)').text();
            $('#del_price').text(price);
            var p = Number(price.substring(1));
              var tot = Number($('#total').text().substring(1));
              $('#final').text(p+tot);
              $('.final').text(p+tot);
              
            //点击选择配送方式
            $('.del').click(function() {
              var tr =$(this).closest('tr'); 
              var del_price = tr.find('td:eq(1)').text();
              $('#del_price').text(del_price);
              var p = Number(del_price.substring(1));
              var tot = Number($('#total').text().substring(1));
              $('#final').text(p+tot);
              $('.final').text(p+tot);
            })         
JS

));