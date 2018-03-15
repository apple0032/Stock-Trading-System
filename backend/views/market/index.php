<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;


$this->title = '大市走勢';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .search-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #F44336;
        border-left: 0px;
        border-bottom: 0px;
        border-right: 0px;
        background: -webkit-linear-gradient(left, rgba(218, 225, 221, 0.38), rgba(219, 254, 254, 0.47));
        background: linear-gradient(to right, rgba(218, 238, 238, 0.56), rgba(166, 191, 196, 0.65));
        font-family: VelinoSans;
    }

    table{
        width:100%;
        table-layout: fixed;
    }
    .tbl-header{
        margin-top: 30px;
        background-color: rgba(255,255,255,0.3);
    }
    .tbl-content{
        height:400px;
        overflow-x:auto;
        margin-top: 0px;
        border: 1px solid rgba(255,255,255,0.3);
    }
    th{
        padding: 20px 15px;
        text-align: left;
        font-weight: 500;
        font-size: 18px;
        color: #000;
        text-transform: uppercase;
    }
    td{
        padding: 15px;
        text-align: left;
        vertical-align:middle;
        font-weight: 300;
        font-size: 16px;
        color: #000;
        border-bottom: solid 1px rgba(255,255,255,0.1);
    }


    /* follow me template */
    .made-with-love {
        margin-top: 40px;
        padding: 10px;
        clear: left;
        text-align: center;
        font-size: 10px;
        font-family: arial;
        color: #fff;
    }
    .made-with-love i {
        font-style: normal;
        color: #F50057;
        font-size: 14px;
        position: relative;
        top: 2px;
    }
    .made-with-love a {
        color: #fff;
        text-decoration: none;
    }
    .made-with-love a:hover {
        text-decoration: underline;
    }


    /* for custom scrollbar for webkit browser*/

    ::-webkit-scrollbar {
        width: 12px;
    }
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }
    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    }

</style>
<?php
$js = <<<JS

    $(".sidebar-toggle").click(function(){
        $('.main-sidebar').css("display", "block");
        $(".skin-blue").toggleClass("sidebar-collapse");
        $(".skin-blue").toggleClass("sidebar-open");
    });

    $(window).on("load resize ", function() {
      var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
      $('.tbl-header').css({'padding-right':scrollWidth});
    }).resize();
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="search-index well well-sm well-white">

    <section>
        <!--for demo wrap-->

        <div class="tbl-header">
            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Index</th>
                    <th>Price</th>
                    <th>Change</th>
                    <th>Trend</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <tbody>
                    <?php
                    foreach ($index as $arr) {  ?>
                        <tr>
                            <td><?= $arr['code'] ?></td>
                            <td><?= $arr['name'] ?></td>
                            <td><?= $arr['point'] ?></td>
                            <td><?= $arr['difference'] ?></td>
                            <td>
                                <?php if($arr['difference'] >0){ ?>
                                    <span style="color:#1bac2a"><i class="fas fa-arrow-up"></i></span>
                                <?php } else { ?>
                                    <span style="color:red"><i class="fas fa-arrow-down"></i></span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }
                    ?>

                </tbody>
            </table>
        </div>
    </section>


    <!-- follow me template -->
    <div class="made-with-love">

    </div>

</div>




