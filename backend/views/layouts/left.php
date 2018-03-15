<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $(".treeview-menu").toggleClass("menu-open");

    });
</script>


<?php
use common\components\Functions;
use adminlte\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<style>
    .treeview{
        margin-bottom: 20px !important;
    }

    .treeview a{
        cursor: default;
    }

    .treeview ul a{
        cursor: pointer;
    }

    .treeview-menu{
        padding-left: 20px;
    }

    .treeview-menu li{
        margin-bottom: 10px !important;
        cursor: pointer !important;
    }

    .pull-right-container{
        display: none;
        margin-top: -3px !important;

    }

    .menu-open{
        display: block;
    }

    .online{
        color:#3eff45;
    }

    .fa-sign-out:before{
        font-family: FontAwesome;
        content: "\f011";
    }

    .fa-tachometer-alt:before{
        font-family: FontAwesome;
        content: "\f015";
    }

    .fa-shopping-cart:before{
        font-family: FontAwesome;
        content: "\f07a";
    }

    .fa-cog:before{
        font-family: FontAwesome;
        content: "\f013";
    }

    .fa-users:before{
        font-family: FontAwesome;
        content: "\f0c0";
    }

    .fa-paper-plane:before{
        font-family: FontAwesome;
        content: "\f1d8";
    }

    .fa-user:before{
        font-family: FontAwesome;
        content: "\f007";
    }

    .fa-globe:before{
        font-family: FontAwesome;
        content: "\f0ac";
    }

    .fa-box:before{
        font-family: FontAwesome;
        content: "\f187";
    }

    .fa-history:before{
        font-family: FontAwesome;
        content: "\f1da";
    }

    .fa-chart-pie:before{
        font-family: FontAwesome;
        content: "\f200";
    }

    .fa-list:before{
        font-family: FontAwesome;
        content: "\f03a";
    }

    .fa-search:before{
        font-family: FontAwesome;
        content: "\f002";
    }

    .fa-bullhorn:before{
        font-family: FontAwesome;
        content: "\f0a1";
    }

    .sidebar-menu>li>a>.fa {
        width: 14px !important;
        margin-right: 5px;
    }

    .sidebar-menu{
        font-size: 16px;
    }

    .treeview-menu>li>a{
        font-size: 16px !important;
    }

</style>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->

		<div class="user-panel">
            <div class="pull-left image">
                <?= Html::img('/frontend/web/images/GravesNerf.jpg', ['class' => 'img-circle', 'alt' => 'User Image']) ?>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->getUser()->identity['username'] ?></p>
                <i class="fa fa-circle online"></i> Online
            </div>
        </div>
        

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <?php


            $left_page_menu = array();


            array_push($left_page_menu, ['label' => '主頁', 'icon' => 'fa fa-tachometer-alt', 'url' => ['/page/home', 'clear-state' => 1]]);

            array_push($left_page_menu,[  'label' => '市場', 'icon' => 'fa fa-globe', 'options' => ['class' => 'treeview'],
            'items' => [
                ['label' => '大市','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-list', 'url' => ['/market']],
                ['label' => '搜尋','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-search', 'url' => ['/search']],
//                ['label' => '新聞','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-bullhorn', 'url' => ['/']],
            ]]);

            array_push($left_page_menu,[  'label' => '個人', 'icon' => 'fa fa-user', 'options' => ['class' => 'treeview'],
            'items' => [
                ['label' => '分析','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-chart-pie', 'url' => ['/']],
                ['label' => '存倉','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-box', 'url' => ['/storage/index','clear-state' => 1]],
                ['label' => '紀錄','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-history', 'url' => ['/history','clear-state' => 1]],
            ]]);

             array_push($left_page_menu,[  'label' => '買賣', 'icon' => 'fa fa-shopping-cart', 'options' => ['class' => 'treeview'],
            'items' => [
                ['label' => '落盤','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-paper-plane', 'url' => ['/buy/index']],
                ['label' => '沽出','linkOptions' => ['class' => 'treeview-menu'], 'icon' => 'fa fa-paper-plane', 'url' => ['/sell/index','clear-state' => 1]],
            ]]);



        if(Yii::$app->user->identity->type == 'admin') {
            array_push($left_page_menu, ['label' => '其他', 'icon' => 'fa fa-cog fa-spin' ,'options' => ['class' => 'treeview'],
                'items' => [
                    ['label' => '用戶資料', 'icon' => 'fa fa-users', 'linkOptions' => ['class' => 'treeview-menu'], 'url' => ['/system-user', 'clear-state' => 1]],
                ]]);
        }

            array_push($left_page_menu, ['label' => '登出', 'icon' => 'fa fa-sign-out', 'url' => ['/site/logout']]);
        ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $left_page_menu,
            ]
        ) ?>

    </section>

</aside>
