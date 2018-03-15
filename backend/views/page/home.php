<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\GridView;
use common\components\yii2GridViewState\widgets\GridView;
use common\models\Approval;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stock System';
//$this->params['breadcrumbs'][] = $this->title;



?>
<?php
$js = <<<JS
        $(".sidebar-toggle").click(function(){
            $('.main-sidebar').css("display", "block");
            $(".skin-blue").toggleClass("sidebar-collapse");
            $(".skin-blue").toggleClass("sidebar-open");
        });

        $(".fa-angle-double-up").click(function(){
             $('.fa-minus-square').click();
             $(".fa-angle-double-down").show();
             $(".fa-angle-double-up").hide();
        });
        
        $(".fa-angle-double-down").click(function(){
             $('.fa-minus-square').click();
             $(".fa-angle-double-up").show();
             $(".fa-angle-double-down").hide();
        });
        
        $(".fa-window-close").click(function(){
            $(".home_profile").fadeOut();
            $(".fa-list-ul").show();
            $(".fa-angle-double-up").click();
            $(".fa-angle-double-down").show();
        });  
        
        $(".fa-list-ul").click(function(){
            $(".home_profile").fadeIn();
            $(".fa-list-ul").hide();
        });  
        
        $(".squaredown").click(function(){ 
             $(".fa-angle-double-down").show();
             $(".fa-angle-double-up").hide();
             $(".fa-minus-square").addClass("squareup");
             $(".fa-minus-square").removeClass("squaredown");  
        });
JS;
$this->registerJs($js);
?>

<style>

    #body{
        background-color: #ffffff;
        margin-left: 10px;
        margin-right: 10px;
        padding-right: 5px;
        padding-top: 10px;
        border-radius: 5px;
    }

    .content-header h1{
        display: none;
    }

    #home_image{
        width:100%;
        height: auto;
        border: 5px solid #ffffff;
        border-radius: 30px;
    }


    .home_profile{
        text-align: center;
        font-size: 17px;
        text-transform: uppercase;
        border: 0px solid transparent;
        background-color: rgba(171, 180, 224, 0.22);
        color: #323232;
        border-radius: 5px;
        font-family: Verdana;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
    }

    .summary{
        color: #40332d;
        background-color: #FFB0C5;
        padding: 5px 5px 0 5px;
        font-size: 20px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 5px solid #c90000;
        font-family: myFont-raleway;
        font-weight: bold;
    }

    .summary_cat{
        padding: 3px;
        border-bottom: 1px solid #dddddd;
        margin-left: 0px;
        margin-right: 0px;
    }

    .fa-minus-square, .fa-window-close{
        font-family: FontAwesome;
        font-style: normal;
        color: #f7fffc;
        cursor: pointer;
    }

    .fa-window-close{
        margin-left: 5px;
    }

    .fa-angle-double-up{
        font-family: FontAwesome;
        font-style: normal;
        cursor: pointer;
    }

    .fa-angle-double-down{
        font-family: FontAwesome;
        font-style: normal;
        cursor: pointer;
        display: none;
    }

    .fa-list-ul{
        font-family: FontAwesome;
        font-style: normal;
        background-color: #3c8dbc;
        color: #ffffff;
        padding: 5px;
        border-radius: 50%;
        cursor: pointer;
        display: none;
    }

    .fa-share-square{
        font-family: FontAwesome;
        font-style: normal;
        color: #ffffff;
    }

</style>

<div class="row" id="body">
    <div class="col-md-5 home_banner">
        <div class="center-block img-responsive">
<!--            <img src="/frontend/web/images/stock.jpg" id="home_image">-->
            <img src="/frontend/web/images/logo3.png" style="width: 100%">
            <br>

            <i class="fas fa-list-ul"></i>

            <div class="home_profile">
                <div class="row no-margin summary">
                    <div class="col-md-12 no-padding">
                        <p style="float: left">
                            <a href="/admin/system-user"><i class="fas fa-share-square"></i></a>
                        </p>
                        Profile Summary
                        <p style="float: right">
                            <i class="fas fa-minus-square squaredown" data-toggle="collapse" data-target="#demo"></i>
                            <i class="fas fa-window-close"></i>
                        </p>
                    </div>
                </div>
                <div style="height: 10px"></div>


                <!-- looping area -->
                <div id="demo" class="collapse in">
                    <div class="row summary_cat">
                        <div class="col-md-6">帳號</div>
                        <div class="col-md-6"><?= Yii::$app->getUser()->identity['username'] ?></div>
                    </div>
                    <div class="row summary_cat">
                        <div class="col-md-6">加入日期</div>
                        <div class="col-md-6"><?= $join_day ?></div>
                    </div>
                    <div class="row summary_cat">
                        <div class="col-md-6">資產總值</div>
                        <div class="col-md-6"><?= $cash_balance + $storage_of_stock + $storage_of_der?></div>
                    </div>
                    <div class="row summary_cat">
                        <div class="col-md-6">現金總值</div>
                        <div class="col-md-6"><?= $cash_balance ?></div>
                    </div>
                    <div class="row summary_cat">
                        <div class="col-md-6">股票總值</div>
                        <div class="col-md-6"><?= $storage_of_stock ?></div>
                    </div>
                    <div class="row summary_cat">
                        <div class="col-md-6">衍生工具</div>
                        <div class="col-md-6"><?= $storage_of_der ?></div>
                    </div>
                    <div class="row summary_cat">
                        <div class="col-md-6">最近一次買賣</div>
                        <div class="col-md-6"><?= $trade ?></div>
                    </div>
<!--                    <div class="row summary_cat">-->
<!--                        <div class="col-md-6">歷史最高買入</div>-->
<!--                        <div class="col-md-6">00005</div>-->
<!--                    </div>-->
                </div>
                <!-- looping area -->

            <div style="height: 30px">
                <i class="fas fa-angle-double-up"></i>
                <i class="fas fa-angle-double-down"></i>
            </div>


            </div>


            <div id="cart">

            </div>


        </div>
    </div>

    <br>

    <div class="col-md-7">
        <iframe src="https://feed.mikle.com/widget/v2/70012/" height="947px" width="100%" class="fw-iframe" scrolling="no" frameborder="0"></iframe>
    </div>
</div>