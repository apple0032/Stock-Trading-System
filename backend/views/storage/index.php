<?php

use yii\helpers\Html;
use common\components\yii2GridViewState\widgets\GridView;
use backend\models\Stock;
use common\models\History;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StorageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的存倉列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    window.onload = function() {


        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title: {
                text: "Your Holding Stock"
            },
            subtitles: [{
                text: "By total price of percentage"
            }],
            data: [{
                type: "pie",
                yValueFormatString: "#,##0.00\"%\"",
                indexLabel: "{label} ({y})",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>


<style>
    .storage-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #c989c9;
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
</style>

<div class="storage-index  well well-sm well-white">

    <div class="row">
        <div class="col-md-8">
            <div class="search_form">
            <?php  echo $this->render('_search', ['model' => $searchModel, 'stock_type_list' => $stock_type_list]); ?>
            </div>

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
                    'stock_string',
                    'stock_code',
                    'amount',

                    //平均買入價
                    $columns[] = [
                        'label' => '平均買入價',
                        'value' => function($model)  {

                            if($model->had_sell != '1') {
                                $average = History::find()->where(['user_id' => Yii::$app->user->identity->id])
                                    ->andWhere(['status' => 1])//仲有貨
                                    ->andWhere(['stock_code' => $model->stock_code])
                                    ->asArray()->all();

                                $average_price = 0;
                                foreach ($average as $av) {
                                    $average_price += $av['price'];
                                }

                                $average = number_format($average_price / count($average), 3);
                            } else {
                                $average = '';
                            }

                            //$average = 'average';
                            return $average;
                        }
                    ],

                    //現價
                    $columns[] = [
                        'label' => '市場價',
                        'format' => 'raw',
                        'value' => function($model)  {

                            $current_price = Stock::GetStockPrice($model->stock_code);
                            $current_price = '<a href="/admin/search?Buy%5Bcode%5D='.$model->stock_code.'">' . $current_price . '</a>';

                            return $current_price;
                        }
                    ],

                    //總值金額
                    $columns[] = [
                        'label' => '參考市值',
                        'value' => function($model)  {
                            $current_price = Stock::GetStockPrice($model->stock_code);
                            $total = $current_price * $model->amount;

                            return $total;
                        }
                    ],

                    $columns[] = [
                        'label' => '帳面盈利/虧損',
                        'format' => 'raw',
                        'value' => function($model)  {

                            $average = History::find()->where(['user_id' => Yii::$app->user->identity->id])
                                ->andWhere(['status' => 1]) //仲有貨
                                ->andWhere(['stock_code' => $model->stock_code])
                                ->asArray()->all();

                            $average_price = 0;
                            foreach ($average as $av){
                                $average_price += $av['price'];
                            }
                            $average = number_format($average_price / count($average),3);


                            $current_price = Stock::GetStockPrice($model->stock_code);

                            $benefit = ($current_price * $model->amount) - ($average * $model->amount);
                            if($benefit > 0){ //賺錢
                                $benefit = '<b style="color:#30bc31">' .$benefit.'</b>';
                            } elseif($benefit < 0) { //蝕死
                                $benefit = '<b style="color:red">'.$benefit.'</b>';
                            } else {
                                $benefit = '-';
                            }

                            //$average = 'average';
                            return $benefit;
                        }
                    ],

                    $columns[] = [
                        'attribute' => 'type',
                        'value' => function($model)  {

                            $stock_type_list = Stock::GetStockTypeList();
                            $type = $stock_type_list[$model->type];

                            return $type;
                        }
                    ],

                    $columns[] = [
                        'label' => '沽出',
                        'format' => 'raw',
                        'value' => function($model)  {

                        $sell = '<div style="text-align: center; font-size: 20px"><a href="/admin/sell/index?sell='.$model->stock_code.'"><i class="fas fa-times-circle"></i></a></div>';

                            return $sell;
                        }
                    ],

                ],
            ]); ?>
        </div>
        <div class="col-md-4">
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
        </div>
    </div>
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
