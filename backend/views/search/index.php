<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;


$this->title = '查詢證券';
//$this->params['breadcrumbs'][] = $this->title;


$request = Yii::$app->request;
$stock = $request->get('Buy');

if(!$stock){
    $stock = 0;
} else {
    $stock = $stock['code'];
    $stock = str_pad($stock,5,'0',STR_PAD_LEFT);
}

$stock = json_encode($stock);

if($stock != '00000'){
    if($bookmarked == false){
        $show = 1; //show
   }  else {
        $show = 0; //hide
  }
} else {
    $show = null;
}

$show = json_encode($show);

?>
<style>
    .search-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #79c6c9;
    }

    .bookmark{
        background-color: #e7505a;
        display: none;
    }

    .bookmarked{
        background-color: #4CAF50;
        display: none;
    }

    .bookmark, .bookmarked{
        text-align: center;
        padding: 5px;
        color: #ffffff;
        border-radius: 5px;
        cursor: pointer;
        font-size: 17px;
    }

    .popup {
        width: 320px;
        height: 25px;
        background-color: rgba(231,84,95,0.8);
        bottom: 20px;
        left: -380px;
        position: fixed;
        border-radius: 5px;
        box-shadow: 0px 25px 10px -15px rgba(0, 0, 0, 0.05);
        transition: 0.5s;
        z-index: 999;
        color: #ffffff;
        font-size: 18px;
        text-align: center;
    }


</style>
<?php
$js = <<<JS

    $(".sidebar-toggle").click(function(){
        $('.main-sidebar').css("display", "block");
        $(".skin-blue").toggleClass("sidebar-collapse");
        $(".skin-blue").toggleClass("sidebar-open");
    });

    
    var show = $show;
    if(show == 1){
        $(".bookmark").show();
    } else if(show==0) {
        $(".bookmarked").show();
    }


    $(".bookmark").click(function(){
        var stock = $stock;
        
        $.ajax({
            url: 'getbookmark',
            type: 'post',
            data: {
            stock: stock,
            },
    
            success: function(data) {
                //alert(data['result']);
                $(".bookmark").hide();
                $(".bookmarked").show();
                $('.popup').css( "left", "10px");
                $('.ns-close').text("已收藏 "+data['stock']+'('+data['stock_name']+')');
                setTimeout(function(){ 
                    $('.popup').css( "left", "-380px" );
                }, 2500);
            }
        });
    });
    
    
    $(".bookmarked").click(function(){
        var stock = $stock;
        
        $.ajax({
            url: 'getbookmark',
            type: 'post',
            data: {
            stock: stock,
            },
    
            success: function(data) {
                $(".bookmarked").hide();
                $(".bookmark").show();
                $('.popup').css( "left", "10px")
                $('.ns-close').text("已從收藏移除 "+data['stock']+'('+data['stock_name']+')');
                setTimeout(function(){ 
                    $('.popup').css( "left", "-380px" );
                }, 2500);
            }
        });
    });


JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="search-index well well-sm well-white">

    <div class="row">
        <div class="col-md-10">
            <?php
            $form = ActiveForm::begin([
                'id' => 'form-daily',
                'type' => ActiveForm::TYPE_INLINE,
                'method' =>  'get',
            ]);
            ?>

            <?= $form->field($model, 'code', ['inputOptions' =>
                ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']])
            ?>

            <?= Html::submitButton('查詢證券', ['class' => 'btn btn-success test']) ?>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-md-2" style="text-align: right">

            <div class="bookmark btn-danger">
                <i class="fas fa-star"></i> 收藏證券
            </div>

            <div class="bookmarked btn-success">
                <i class="fas fa-bookmark"></i> 已收藏(點擊移除)
            </div>

        </div>
    </div>

    <?php if($search != null) {  ?>

        <iframe src="http://services1.aastocks.com/Web/CHSU/Quote.aspx?language=chi&platform=desktop&symbol=<?php echo $search ?>" style="width:100%; height:650px; border: 1px solid #ccc; padding:10px;"></iframe>

    <?php }?>

    <div class="popup">
        <div class="ns-close"></div>
    </div>

</div>




