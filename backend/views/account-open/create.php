<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\AccountOpen */

$this->title = 'Create Account Open';
$this->params['breadcrumbs'][] = ['label' => 'E-Appointment Application', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-open-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
