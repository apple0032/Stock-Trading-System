<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SystemUser */

$this->title = 'Update System User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'System Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="system-user-update">

    <!-- h1><?= Html::encode($this->title) ?></h1 -->

    <?= $this->render('_form', [
        'model' => $model,
        'type_list' => $type_list,
        'old_password' => $old_password,
        'status_list' => $status_list,
    ]) ?>

</div>
