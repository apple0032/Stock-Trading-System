<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?php
        echo $form->field($model, 'password_hash')->passwordInput(['value' => '', 'maxlength' => true])->label('Password');
    ?>

    <?php

        echo $form->field($model, 'password_confirm')->passwordInput(['maxlength' => true])->label('Confirm Password');
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php // echo $form->field($model, 'departments')->textarea(['rows' => 6]) ?>

    <label class="panel-title">Departments</label>
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php
                $user_departments = json_decode($model->departments, true);

                $isset_user_departments = ($user_departments != NULL && $user_departments != '');

                echo "<div>";
                foreach ($department_list as $key => $department) {
                    $isset_department = isset($user_departments[$key]);
                        echo "<div><input type='checkbox' id='departments_".$key."' name='departments[".$key."]' value='1' ". ($isset_user_departments ? $isset_department ? 'checked="checked"' : '' : '') .">
                        &nbsp;
                        ".$department."
                        </input>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>";
                }
                echo "</div>";
            ?>
        </div>
    </div>

    <?php
        echo $form->field($model, 'superuser')->radioList($status_list);
    ?>

    <?php
        echo $form->field($model, 'status')->radioList($status_list);
    ?>

    <?php // echo $form->field($model, 'created_at')->textInput() ?>

    <?php // echo $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
