<!-- 商品列表 start-->
<div class="goodslist mt10">
    <ul>
    </ul>
        <?php foreach($goods as $good):?>
        <li>
            <dl>
                <dt><?=\yii\helpers\Html::a(\yii\helpers\Html::img('http://admin.yii2shop.com'.$good->logo),['site/goods','id'=>$good->id])?> </a></dt>
                <dd><?=\yii\helpers\Html::a($good->name,['site/goods','id'=>$good->id])?></dt>
                <dd><strong><?='￥'.$good->shop_price?></strong></dt>
                <dd><a href=""><em>已有10人评价</em></a></dt>
            </dl>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
<!-- 商品列表 end-->