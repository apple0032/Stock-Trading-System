<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AccountOpenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use frontend\models\AccountOpenTime;

$this->title = 'E-Appointment Application';
$this->params['breadcrumbs'][] = $this->title;

$time_list = AccountOpenTime::getList();
?>
<div class="account-open-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if($pagePermission['Edit'] == 1) {
            echo Html::a('Create E-Appointment Application', ['create'], ['class' => 'btn btn-success']);
        } ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'eng_name',
            'eng_surname',
            'email:email',
            // 'phone',
            // 'district:ntext',
            // 'arranged',
            // 'exist_consultant',
            // 'reply_time:datetime',
            [
                'label' => 'Reply Time',
                'attribute' => 'reply_time',
                'filter' => $time_list,
                'value' => function ($model) {
                    $time_list = AccountOpenTime::getList();
                    return $time_list[$model->reply_time];
                }
            ],
            // 'interested:ntext',
            // 'status',
            [
                'label' => 'Status',
                'attribute' => 'status',
                'filter' => $status_list,
                'value' => function ($model) {
                    return $model->status == 1 ? "Active" : "Disable";
                }
            ],
            'create_date:ntext',
            // 'update_date:ntext',

            ['class' => 'yii\grid\ActionColumn', 'visibleButtons' => ['update' => ($pagePermission['Edit'] == 1 ? true : false), 'view' => ($pagePermission['Edit'] == 1 || $pagePermission['View'] == 1 ? true : false), 'delete' => ($pagePermission['Edit'] == 1 ? true : false)]]
        ],
    ]); ?>
</div>
