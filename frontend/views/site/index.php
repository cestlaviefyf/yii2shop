<!-- 导航条部分 start -->
<div class="nav w1210 bc mt10">
    <!--  商品分类部分 start-->
    <div class="category fl"> <!-- 非首页，需要添加cat1类 -->
        <div class="cat_hd">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
            <h2>全部商品分类</h2>
            <em></em>
        </div>
        <div class="cat_bd">
            <?php foreach ($categories as $k=>$category)://遍历所有一级分类?>
            <div class="cat <?=$k==0?"item1":""?>">
                <h3><?=\yii\helpers\Html::a($category->name,['site/list','cate_id'=>$category->id])?><b></b></h3>
                <div class="cat_detail">
                    <?php foreach ($category->children as $k2=>$children)://遍历二级分类?>
                    <dl <?=$k2==0?'class="dl_1st"':''?>>
                        <dt><?=\yii\helpers\Html::a($children->name,['site/list','cate_id'=>$children->id])?></dt>
                    <?php foreach ($children->children as $cate):?>
                        <dd>
                            <?=\yii\helpers\Html::a($cate->name,['site/list','cate_id'=>$cate->id])?>
                        </dd>
                    <?php endforeach;?>
                    </dl>
                    <?php endforeach;?>
                </div>

            </div>
            <?php endforeach;?>
        </div>

    </div>
    <!--  商品分类部分 end-->