<?php
use yii\helpers\Html;
use backend\models\Stock;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php
    //get hk stock market status
    $status = Stock::GetMarketStatus();

    //get hsi
    $hsi = Stock::GetMarketHSI();

    //get world index
    $world_index = Stock::GetMarketIndex();
?>

<?php

$js = <<<JS
        $(".sidebar-toggle").click(function(){
            $('.main-sidebar').css("display", "block");
            $(".skin-blue").toggleClass("sidebar-collapse");
            $(".skin-blue").toggleClass("sidebar-open");
        });

JS;
$this->registerJs($js);
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var status = <?php echo json_encode($status); ?>;

        if(status == true){
            $("#market_status").append('<i class="status_circle" style="color:#3eff45"></i> 開市中');
        } else {
            $("#market_status").append('<i class="status_circle" style="color:red"></i> 已收市');
        }
    });
</script>

<style>
    @font-face {
        font-family: myFont-raleway;
        src:url('/frontend/web/css/font/Raleway-Light.ttf'),
        url('/frontend/web/css/font/raleway-light-webfont.eot'),
        url('/frontend/web/css/font/raleway-light-webfont.woff');
    }

    @font-face {
        font-family: VelinoSans;
        src:url('/frontend/web/css/font/VelinoSans-Bold.ttf'),
        url('/frontend/web/css/font/VelinoSans-Bold.eot'),
        url('/frontend/web/css/font/VelinoSans-Bold.woff');
    }

    @font-face {
        font-family: playfair;
        src:url('/frontend/web/css/font/PlayfairDisplay-Regular.ttf');
    }

    .fas, .fa{
        font-family: FontAwesome;
        font-style: normal;
    }

    .server_time{
        text-align: right;
        display: inline-block;
        color: #ffffff;
        padding-top: 13px;
        font-size: 16px;
        height: 50px;
        padding-left: 20px;
        padding-right: 20px;
        background-color: #0b6db5;
    }

    .server_time:hover{
        /*background-color: #0b60a9;*/
        /*cursor: pointer;*/
    }

    #server_time{
        display: inline-block;
        margin-bottom: 10px;
        margin-right: 20px;
    }

    #market_status,#market_index{
        display: inline-block;
    }

    #market_index{
        color: white;
        width: 800px;
        height: 45px;
        font-size: 19px;
    }

    .status_circle:before{
        font-family: FontAwesome;
        content: "\f111";
        font-style: normal;
    }

    .price_change{
        display: inline-block;
        font-size: 13px;
        margin: 0px;
    }

    .world_index{
        display: inline-block;
        margin-left: 10px;
    }

    #site_name a{
        font-family: playfair;
        color:white;
        font-size:23px
    }

    .user-header{
        background-color: white !important;
    }

    .dropdown-menu{
        border: 1px solid rgba(124, 132, 141, 0.45);
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        font-family: myFont-raleway;
    }

    .navbar_img{
        height: 50px;
        width: auto;
        padding-top: 5px;
        padding-bottom: 5px;
        margin-right: 5px;
    }

    .user-paneller{
        padding-top: 0px !important;
        padding-bottom: 0px !important;
    }

    .navbar-nav>.user-menu>.dropdown-menu>li.user-header>p{
        color: black !important;
        text-transform: uppercase;
        font-size: 20px;
    }

    .user-panel{
        font-family: myFont-raleway;
    }
</style>
<script>
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('server_time').innerHTML =
            h + " 小時 " + m + " 分鐘 " + s + ' 秒';
        var t = setTimeout(startTime, 500);

    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i}  // add zero in front of numbers < 10
        return i;
    }

    Date.prototype.addHours= function(h){
        this.setHours(this.getHours()+h);
        return this;
    }
</script>

<header class="main-header">

    <?php //echo Html::a(Yii::$app->params['siteName'] . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <div class="logo">
<!--        <img src="/frontend/web/images/backendlogo.png" style="width: 100%">-->
        <div id="site_name"><a href="/admin/page/home">STOCK SYSTEM</a></div>
    </div>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="background-color: #0b6db5">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <body onload="startTime()">

            <div class="server_time">
                現在時間 : <div id="server_time"></div>

                市場狀態 : <div id="market_status"></div>


            </div>

            <div id="market_index">

                <marquee direction = "left" scrollamount="10">

                    <div class="world_index">
                        恆生指數 : <?= $hsi['hsi']; ?>
                        <p class="price_change">
                            <?php if($hsi['hsi_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $hsi['hsi_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $hsi['hsi_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="world_index">
                        道瓊斯工業指數 : <?= $world_index['INDU'] ?>
                        <p class="price_change">
                            <?php if($world_index['INDU_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $world_index['INDU_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $world_index['INDU_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="world_index">
                        標準普爾指數 : <?= $world_index['SPX'] ?>
                        <p class="price_change">
                            <?php if($world_index['SPX_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $world_index['SPX_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $world_index['SPX_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="world_index">
                        納斯達克指數 : <?= $world_index['NASDAQ'] ?>
                        <p class="price_change">
                            <?php if($world_index['NASDAQ_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $world_index['NASDAQ_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $world_index['NASDAQ_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="world_index">
                        英國富時指數 : <?= $world_index['UKX'] ?>
                        <p class="price_change">
                            <?php if($world_index['UKX_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $world_index['UKX_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $world_index['UKX_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="world_index">
                        上海綜合指數 : <?= $world_index['SHCOMP'] ?>
                        <p class="price_change">
                            <?php if($world_index['SHCOMP_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $world_index['SHCOMP_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $world_index['SHCOMP_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                    <div class="world_index">
                        日經平均指數 : <?= $world_index['NKY'] ?>
                        <p class="price_change">
                            <?php if($world_index['NKY_change'] > 0){  ?>
                                <span style="color:#3eff45"><i class="fas fa-arrow-up"></i> <?= $world_index['NKY_change'] ?></span>
                            <?php } else { ?>
                                <span style="color:red"><i class="fas fa-arrow-down"></i> <?= $world_index['NKY_change'] ?></span>
                            <?php } ?>
                        </p>
                    </div>

                </marquee>
            </div>

        </body>



        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <!-- Messages: style can be found in dropdown.less-->
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle user-paneller" data-toggle="dropdown">
<!--                        <img src="--><?//= $directoryAsset ?><!--/img/user2-160x160.jpg" class="user-image" alt="User Image"/>-->
                        <span class="hidden-xs">
                            <img src="/frontend/web/images/GravesNerf.jpg" class="img-circle navbar_img" alt="User Image"/>
                            <?= ucfirst(Yii::$app->getUser()->identity['username'])?>
                            <i class="fas fa-caret-down"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/frontend/web/images/GravesNerf.jpg" class="img-circle" alt="User Image"/>

                            <p>
                                <?= ucfirst(Yii::$app->getUser()->identity['username'])?>
                            </p>
                        </li>
                        <!-- Menu Body -->
<!--                        <li class="user-body">-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Followers</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Sales</a>-->
<!--                            </div>-->
<!--                            <div class="col-xs-4 text-center">-->
<!--                                <a href="#">Friends</a>-->
<!--                            </div>-->
<!--                        </li>-->

                        <!-- Menu Footer -->
                        <li class="user-footer">

                            <div class="pull-right">
                                <?= Html::a(
                                    '<i class="fa fa-power-off"></i>',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->

<!--                --><?php //if(Yii::$app->getUser()->identity['username'] == 'admin') {?>
<!--                    <li>-->
<!--                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
<!--                    </li>-->
<!--                --><?php //} ?>

            </ul>
        </div>
    </nav>
</header>
