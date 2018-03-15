<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\AccountOpen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-open-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eng_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'eng_surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'district')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'arranged')->textInput() ?>

    <?= $form->field($model, 'exist_consultant')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reply_time')->textInput() ?>

    <?= $form->field($model, 'interested')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_date')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'update_date')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
