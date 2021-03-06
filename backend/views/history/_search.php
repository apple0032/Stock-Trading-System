<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\HistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'date_time') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'stock_code') ?>
        </div>

        <div class="col-md-3">
            <?php echo $form->field($model, 'stock_string') ?>
        </div>

        <div class="col-md-3">
            <?php echo $form->field($model, 'is_buy')->dropdownList($boolean_list, ['prompt'=>'選擇..']) ?>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <?= Html::submitButton('搜尋', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('重設', ['class' => 'btn btn-default']) ?>
                <a href="/admin/history?clear-state=1"><div class="btn btn-default">清除搜尋</div></a>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
