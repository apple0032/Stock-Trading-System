<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccountOpen */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'E-Appointment Application', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$time = $time_list;

$interested_array = json_decode($model->interested, true);

$interested_str = "";

foreach ($interested_array as $key => $value) {
    # code...
    if($key === 'other' && is_null($value) == false && empty($value) == false && $value != "") {
        $interested_str .= ': ' . $value;
    } else {
        $interested_str .= $value;
    }

    $interested_str .= ' ';
}

?>
<div class="account-open-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
            if($pagePermission['Edit'] == 1) {
                echo Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

                echo Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            // 'name',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_name'),
                'attribute' => 'name'
            ],
            // 'eng_name',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_eng_name'),
                'attribute' => 'eng_name'
            ],
            // 'eng_surname',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_eng_surname'),
                'attribute' => 'eng_surname'
            ],
            // 'email:email',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_email'),
                'attribute' => 'email'
            ],
            // 'phone',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_phone'),
                'attribute' => 'phone'
            ],
            // 'district:ntext',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_district'),
                'attribute' => 'district'
            ],
            // 'arranged',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_arranged'),
                'attribute' => 'arranged',
                'value' => $model->arranged  == 0 ? 'No' : 'Yes',
            ],
            // 'exist_consultant',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_exist_consultant'),
                'attribute' => 'exist_consultant'
            ],
            // 'reply_time',
            [
                'label' => Yii::t('frontend/account-open', 'ac_open_reply_time'),
                'attribute' => 'reply_time',
                'value' => $time[$model->reply_time],
            ],
            // 'interested:ntext',
            [
                'label' => Yii::t('frontend/account-open',  'ac_open_interested'),
                'attribute' => 'interested',
                'value' => $interested_str,
            ],
            // 'status',
            [
                'attribute' => 'status',
                'value' => $model->status  == 0 ? 'Disable' : 'Active',
            ],
            'create_date:ntext',
            // 'update_date:ntext',
        ],
    ]) ?>

</div>
