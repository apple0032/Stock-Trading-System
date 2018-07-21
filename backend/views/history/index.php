<?php

use yii\helpers\Html;
use common\components\yii2GridViewState\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '交易紀錄';
//$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .history-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #c90000;
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
</style>


<div class="history-index  well well-sm well-white">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <div class="search_form">
        <?php echo $this->render('_search', ['model' => $searchModel, 'boolean_list' => $boolean_list]); ?>
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
            //'date',
            'date_time',
            'stock_code',
             'stock_string',
            $columns[] = [
                'attribute' => 'is_buy',
                'value' => function($model)  {
                    if($model->is_buy == 1){
                        $is_buy = '買入';
                    } else {
                        $is_buy = '賣出';
                    }
                    return $is_buy;
                }
            ],
             'amount',
             'price',
            $columns[] = [
                'attribute' => 'status',
                'value' => function($model)  {
                    if($model->status == 1){
                        $status = '全數成交';
                    } else {
                        $status = '取消';
                    }
                    return $status;
                }
            ],

        ],
    ]); ?>
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
