<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$request = Yii::$app->request;
$sell = $request->get('sell');

if(!$sell){
    $sell = 0;
}

$sell = json_encode($sell);

$this->title = '沽出證券';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .trade-index{
        padding: 10px 30px 30px 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #c90000;
    }

    .trade-label{
        padding-top: 5px;
        font-size: 16px;
        font-weight: bold;
    }

    #buy-stock_type{
        display: none;
    }

    .check_storage{
        margin-bottom: 15px;
    }

    /** storage table **/

    #holding {
        border-spacing: 1;
        border-collapse: collapse;
        background: white;
        border-radius: 6px;
        overflow: hidden;
        max-width: 1000px;
        width: 100%;
        margin: 0 auto;
        position: relative;
    }
    #holding * {
        position: relative;
    }
    #holding td, #holding th {
        padding-left: 8px;
    }
    #holding thead tr {
        height: 60px;
        background: #EF5350;
        font-size: 16px;
        color: #ffffff;
    }
    #holding tbody tr {
        height: 48px;
        border-bottom: 1px solid #E3F1D5;
    }
    #holding tbody td {
        font-size: 15px;
    }

    #holding tbody tr:last-child {
        border: 0;
    }
    #holding td, #holding th {
        text-align: left;
    }
    #holding td.l, #holding th.l {
        text-align: right;
    }
    #holding td.c, #holding th.c {
        text-align: center;
    }
    #holding td.r, #holding th.r {
        text-align: center;
    }

    @media screen and (max-width: 35.5em) {
        #holding {
            display: block;
        }
        #holding > *, #holding tr, #holding td, #holding th {
            display: block;
        }
        #holding thead {
            display: none;
        }
        #holding tbody tr {
            height: auto;
            padding: 8px 0;
        }
        #holding tbody tr td {
            padding-left: 45%;
            margin-bottom: 12px;
        }
        #holding tbody tr td:last-child {
            margin-bottom: 0;
        }
        #holding tbody tr td:before {
            position: absolute;
            font-weight: 700;
            width: 40%;
            left: 10px;
            top: 0;
        }
        #holding tbody tr td:nth-child(1):before {
            content: "Code";
        }
        #holding tbody tr td:nth-child(2):before {
            content: "Stock";
        }
        #holding tbody tr td:nth-child(3):before {
            content: "Cap";
        }
        #holding tbody tr td:nth-child(4):before {
            content: "Inch";
        }
        #holding tbody tr td:nth-child(5):before {
            content: "Box Type";
        }
    }

    .control-sidebar-dark{
        display: none;
    }

    .control-sidebar-bg{
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

    var sell = $sell;
    if(sell != 0){
        getstockinfo(sell);
    }

    $('#buy-code').change(function(){
        var stock = $(this).val();

        getstockinfo(stock);
    });
    
    function getstockinfo(stock) {
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
                    $('#buy-amount').val(data['own']);
                } else {
                    alert('系統尋找不到此證券。');
                    $('#buy-code').val('');
                    $('#buy-name').val('');
                    $('#buy-current_price').val('');
                    $('#buy-lotsize').val('');
                    $('#buy-stock_type').val('');
                }
            }
        });
    }
    
    

    $('#buy-amount').change(function(){
        var amount = $(this).val();
        var lotsize = $('#buy-lotsize').val();
        var current_price = $('#buy-current_price').val();
        
        if(lotsize.length > 0){

            var total = 0;
            total = amount * current_price;
            total = Math.ceil(total);
            $('#buy-total').val(total);
            
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
    <button type="button" class="btn btn-danger check_storage" data-toggle="modal" data-target="#popup-storage">查看所持證券</button>

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
                        <?= Html::submitButton('沽出', ['class' => 'btn btn-danger']) ?>
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



<div class="modal fade" id="popup-storage" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                <table id="holding">
                    <thead>
                    <tr>
                        <th>證券號碼</th>
                        <th>證券名稱</th>
                        <th>持貨量</th>
                        <th>現價</th>
                        <th>參考市值</th>
                    </tr>
                    <thead>
                    <tbody>
                        <?php foreach ($storage as $holding) {  ?>
                                <tr>
                                    <td><?= $holding['code'] ?></td>
                                    <td><?= $holding['label'] ?></td>
                                    <td><?= $holding['amount'] ?></td>
                                    <td><?= $holding['price'] ?></td>
                                    <td><?= $holding['sum_of_price'] ?></td>
                                </tr>
                        <?php }?>
                    </tbody>
                    <table/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
            </div>
        </div>

    </div>
</div>



