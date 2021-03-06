<?php

use yii\helpers\Html;
use common\components\yii2GridViewState\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SystemUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用戶資料';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-index  well well-sm well-white">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create System User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
		'as filterBehavior' => \common\components\yii2GridViewState\FilterStateBehavior::className(),
        'dataProvider' => $dataProvider,
		'tableOptions' => [
			'class' => 'table table-striped table-bordered table-hover datatables-view-grid',
		],
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn',
				'visibleButtons' => [
					'delete' => false,
				]
			],

            'id',
            'username',
    $columns[] = [
        'label' => 'Type',
        'attribute' => 'type',
        'filter' => $type_list,
        'value' => function($model, $index, $dataColumn) use ($type_list) {
            return isset($type_list[$model->type]) ? $type_list[$model->type] : '';
        }
    ],
            'cash',
            'status:boolean',
            //'create_user',
            // 'create_date',
            // 'update_user',
            // 'update_date',

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
