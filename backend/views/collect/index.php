<?php

use yii\helpers\Html;
use common\components\yii2GridViewState\widgets\GridView;
use backend\models\Stock;
use common\models\History;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StorageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的收藏列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .storage-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #FFCC80;
    }

    .summary{
        display: none;
    }

    #w1-filters{
        display: none;
    }

    thead{
        color: #337ab7;
    }

    .table-bordered{
        border: 2px solid #eee;
    }

    .search_form{
        background-color: rgba(11, 147, 213, 0.09);
        padding: 15px 15px 0 15px;
    }

    .canvasjs-chart-credit{
        display: none !important;
    }

    .table-bordered th:nth-child(5){
        text-align: center;
    }

    .add_collect{
        margin-bottom: 15px;
        margin-top: -10px;
    }

    .add_box{
        font-size: 16px;
    }

    .add_notice{
        padding: 10px;
        margin-bottom: 20px;
        background-color: #c0edf1;
        border-radius: 5px;
        border-left: 5px solid #58d0da;
        font-size: 18px;
        font-weight: bold;
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

    .fa-times-circle{
        font-size: 22px;
        color:#3c8dbc;
    }

    .delete_stock{
        cursor: pointer;
    }

</style>

<?php
$js = <<<JS

    $(".sidebar-toggle").click(function(){
        $('.main-sidebar').css("display", "block");
        $(".skin-blue").toggleClass("sidebar-collapse");
        $(".skin-blue").toggleClass("sidebar-open");
    });

    
    $("#add-code").change(function(){
        
        var stock = $(this).val();
        
        $.ajax({
            url: 'getstockinfo',
            type: 'post',
            data: {
            stock: stock,
            },

            success: function(data) {
                if(data['name'] != null){
                    $('#add-code').val(data['stock']);
                    $('#add-name').val(data['name']);
                    $('#add-current_price').val(data['price']);
                    //$(".close").click()
                } else {
                    alert('系統沒有此證券，請再輸入');
                    $('#add-code').val('');
                    $('#add-name').val('');
                    $('#add-current_price').val('');
                }
            }
        });
        
    });

    $(".add_stock").click(function(){
        
        var stock_name =  $('#add-name').val();
        var stock = $('#add-code').val();
        
        if(stock_name != ''){
            
            $.ajax({
                url: 'getbookmark',
                type: 'post',
                data: {
                stock: stock,
                },
        
                success: function(data) {
                    //alert(data['db_id']);

                    if(data['result'] == 'success'){
                        $(".close").click();
                        $('.popup').css( "left", "10px");
                        $('.ns-close').text("已收藏 "+data['stock']+'('+data['stock_name']+')');
                        setTimeout(function(){ 
                            $('.popup').css( "left", "-380px" );
                        }, 2500);
                        
                        //插入新行
                        $('.table-striped').find('tbody').append('' +
                         '<tr data-key="'+data['db_id']+'" style="background-color:rgba(231,84,95,0.2);">' +
                          '<td>New</td><td>'+data['stock']+'</td>' +
                           '<td>'+data['stock_name']+'</td>' +
                            '<td><a href="/admin/search/index?Buy%5Bcode%5D='+data['stock']+'">'+data['price']+'</a></td>' +
                             '<td>' +
                             
                              '<div style="text-align: center; font-size: 20px" id="'+data['db_id']+'" class="delete_stock">' +
                              '<i class="fas fa-times-circle"></i>' +
                              '</div>' +
                                
                                '</td>' +
                              '</tr>');
                        
                        
                    } else {
                        //alert('bookmarked')
                        $(".close").click();
                        $('.popup').css( "left", "10px");
                        $('.ns-close').text(data['stock']+'('+data['stock_name']+') 已在收藏庫中');
                        setTimeout(function(){ 
                            $('.popup').css( "left", "-380px" );
                        }, 2500);
                    }
                }
            });
            
        } else {
            alert('請輸入證券號碼')
        }
 
    });
        
    $(".add_collect").click(function(){ 
        $('#add-code').val('');
        $('#add-name').val('');
        $('#add-current_price').val('');
    });
    
  
     $('body').delegate('.delete_stock','click',function() {   
        var db_id =  $(this).attr("id"); //this.id
        //alert(db_id);  
        
        //$(".table-striped").find("[data-key='" + db_id + "']").fadeout();
        
        $.ajax({
            url: 'delbookmark',
            type: 'post',
            data: {
            id: db_id,
            },
    
            success: function(data) {
                //alert(data['id']);
                $(".table-striped").find("[data-key='" + data['id'] + "']").remove();
                $('.popup').css( "left", "10px");
                $('.ns-close').text('已從收藏庫移除');
                setTimeout(function(){ 
                $('.popup').css( "left", "-380px" );
                }, 2500);
            }
        });
        
        
    });
    
    

JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="storage-index  well well-sm well-white">

    <button type="button" class="btn btn-primary add_collect" data-toggle="modal" data-target="#popup-storage"><i class="fas fa-plus-circle"></i> 新增證券</button>


    <div class="row">
        <div class="col-md-10">


            <?= GridView::widget([
                'as filterBehavior' => \common\components\yii2GridViewState\FilterStateBehavior::className(),
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered table-hover datatables-view-grid',
                ],
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
        //            ['class' => 'yii\grid\ActionColumn',
        //				'visibleButtons' => [
        //					'delete' => false,
        //				]
        //			],

                    //'id',
                    //'user_id',
                    'stock_code',
                    'stock_string',

                    $columns[] = [
                        'label' => '市場價',
                        'format' => 'raw',
                        'value' => function($model)  {

                            $current_price = Stock::GetStockPrice($model->stock_code);
                            $current_price = '<a href="/admin/search/index?Buy%5Bcode%5D='.$model->stock_code.'">' . $current_price . '</a>';

                            return $current_price;
                        }
                    ],

                    $columns[] = [
                        'label' => '從收藏移除',
                        'format' => 'raw',
                        'value' => function($model)  {

                            $sell = '
                            <div style="text-align: center; font-size: 20px" id="'.$model->id.'" class="delete_stock">
                                    <i class="fas fa-times-circle"></i>
                            </div>';

                            return $sell;
                        }
                    ],


                ],
            ]); ?>
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

                <div class="add_notice">
                    請輸入證券號碼以加入收藏
                </div>


                <?php $form = ActiveForm::begin(); ?>

                <div class="row add_box">
                    <div class="col-md-2 no-margin">
                        證券號碼
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'code', ['inputOptions' =>
                            ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']])
                            ->textInput(['maxlength' => 5])->label(false) ?>
                    </div>
                </div>

                <div class="row add_box">
                    <div class="col-md-2 no-margin">
                        證券名稱
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>

                <div class="row add_box">
                    <div class="col-md-2 no-margin">
                        證券現價
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($model, 'current_price')->textInput(['maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>

                <button type="button" class="btn btn-danger add_stock">加入</button>

                <?php ActiveForm::end(); ?>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close" data-dismiss="modal">關閉</button>
            </div>
        </div>

    </div>
</div>


<div class="popup">
    <div class="ns-close"></div>
</div>


<?php
$js = <<<JS
$('.datatables-view-grid').DataTable( {
        scrollY:        '60vh',
        scrollX: true,
		scrollCollapse: true,
        paging:         false,
		ordering: false,
		searching: false,
		initComplete: function( settings, json ) {
			this.find('.filters').remove(); // fix deduplcated filter row!
		}
} ); 
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>
