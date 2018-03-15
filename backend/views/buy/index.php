<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = '落盤買入';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .trade-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #2db131;
    }

    .trade-label{
        padding-top: 5px;
        font-size: 16px;
        font-weight: bold;
    }

    #buy-stock_type{
        display: none;
    }
</style>
<?php
$js = <<<JS

    $(".sidebar-toggle").click(function(){
        $('.main-sidebar').css("display", "block");
        $(".skin-blue").toggleClass("sidebar-collapse");
        $(".skin-blue").toggleClass("sidebar-open");
    });


    $('#buy-code').change(function(){
        var stock = $(this).val();
        
        
        $.ajax({
            url: 'getstockinfo',
            type: 'post',
            data: {
            stock: stock,
            },
    
            success: function(data) {
                if(data['name'] != null){
                    $('#buy-code').val(data['stock']);
                    $('#buy-name').val(data['name']);
                    $('#buy-current_price').val(data['price']);
                    $('#buy-lotsize').val(data['lotsize']);
                    $('#buy-stock_type').val(data['uaCode']);
                } else {
                    alert('系統沒有此證券。');
                    $('#buy-code').val('');
                    $('#buy-name').val('');
                    $('#buy-current_price').val('');
                    $('#buy-lotsize').val('');
                    $('#buy-stock_type').val('');
                }
            }
        });
    });

    $('#buy-amount').change(function(){
        var amount = $(this).val();
        var lotsize = $('#buy-lotsize').val();
        var current_price = $('#buy-current_price').val();
        
        if(lotsize.length > 0){
            if((amount % lotsize) != 0){
                alert('股數以每手為單位!');
                $(this).val('');
                $('#buy-total').val('');
            } else {
           
            var total = 0;
            total = amount * current_price;
            total = Math.ceil(total);
            $('#buy-total').val(total);
            
            }
            
        } else {
            alert('請輸入證券號碼');
            $(this).val('');
        }
    });
    
    
    // setInterval(function(){ 
    //      $("#buy-code").trigger("change");
    // }, 60000);


JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="trade-index well well-sm well-white">

    <div class="row">
        <div class="col-md-5">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-2 trade-label">
                    證券號碼
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => 5])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 trade-label">
                    證券名稱
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 trade-label">
                    證券現價
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'current_price')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 trade-label">
                    每手數目
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'lotsize')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 trade-label">
                    落盤股數
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'amount')->textInput(['maxlength' => true])->label(false) ?>股數以每手為單位
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-2 trade-label">
                    總金額
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'total')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 trade-label">
                    落盤類型
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'btype')->dropdownList($btype, ['prompt'=>'Choose...'])->label(false) ?>
                </div>
            </div>

                <?= $form->field($model, 'stock_type')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>

            <div class="row">
                <div class="col-md-6 col-md-offset-2" style="text-align: right">
                    <div class="form-group">
                        <?= Html::submitButton('買入', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-7">

            <b>重要提示</b>
            <p>
            限價盤是以用戶指定的價格作配對。用戶以限價盤輸入買盤後，用戶指定的價格將被視作最高買入價，買盤的最終成交價將不會高於該指定的價格；而用戶以限價盤輸入賣盤後，用戶指定的價格將被視作最低賣出價，賣盤的最終成交價將不會低於該指定的價格。
            </p>

            市價盤是用戶沒有指定價格的買賣盤。在持續交易時段於交易系統輸入市價盤後，買賣盤按處理指示時的市場買入／沽出價執行交易。發出「市價盤」指示後，用戶應查詢交易狀況及有關指示的成交結果。用戶必須注意最終成交價有可能大幅偏離發出交易指示時的現價，特別是流通量較少的股票。在上午或下午剛開市時的交易時段，由於需要處理開市前已囤積的指示，成交價出現偏離的機會亦較大。

            <br><br>

            <b>風險披露</b>
            <p>以下風險披露聲明不能披露所有涉及的風險。投資平台提供的資料僅作參考用途，並不構成對任何人作出買賣、認購或交易任何投資產品或服務的要約、招攬、建議、意見或任何保證。往績數字並非未來表現的指標。在進行交易或投資前，您應負責本身的資料蒐集及研究。你應按本身的財政狀況及投資目標謹慎考慮是否適宜進行交易或投資。建議您於進行交易或投資前應尋求獨立的財務及專業意見。假如您不確定或不明白以下風險披露聲明或進行交易或投資所涉及的性質及風險，您應尋求獨立的專業意見。
            </p>

            <b>證券交易的風險</b>
            <p>證券價格有時可能會非常波動。證券價格可升可跌，甚至變成毫無價值。買賣證券未必一定能夠賺取利潤，反而可能會招致損失。</p>

            <b>認股證及牛熊證交易的風險</b>
            <p>認股證及牛熊證的價格可急升或急跌，投資者或會損失全部投資。掛鈎資產的過往表現並非日後表現的指標。閣下應確保理解認股證及牛熊證的性質，並仔細研究認股證及牛熊證的有關上市文件中所載的風險因素，如有需要，應尋求專業意見。沒有行使的認股證於屆滿時將沒有任何價值。牛熊證設有強制贖回機制，當掛鈎資產價格達到贖回價時會即時提早終止，在這種情況下：（i）N類牛熊證投資者將不會收取任何現金付款；及（ii）R類牛熊證投資者或會收取稱為「剩餘價值」的現金付款（可能為零）。</p>

            <b style="color: rgb(245, 245, 245)">注意：此為模擬交易，唔好當真！</b>
        </div>
    </div>


</div>




