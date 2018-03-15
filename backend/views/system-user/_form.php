<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

//use kartik\touchspin\TouchSpin;
//use kartik\widgets\DatePicker;
//use backend\components\CKEditor;
//use backend\components\KCFinderInputWidget;
//use iutbay\yii2kcfinder\KCFinder;


/*
// =============== example ==============
echo $form->field($model, 'date_expire')
	->widget(DatePicker::className(), [
		'options' => [
			'placeholder'   =>  'yyyy-mm-dd',
		],
		'pickerButton' => [
			'title' => ' ',
		],
		'removeButton' => [
			'title' => ' ',
		],
		'pluginOptions' => [
			'autoclose' => true,
			'format' => 'yyyy-mm-dd',
		],
	]);

echo $form->field($model, 'status')->radioList($status_list);
echo $form->field($model, 'dropdownfield')->dropdownList($dropdownfield_list, ['prompt' => '-']);
echo $form->field($model, 'html_en')->widget(CKEditor::className(), [
	'options' => ['rows' => 6],
	'preset' => 'full'
])->label('HTML Content En');

*/



/* @var $this yii\web\View */
/* @var $model common\models\SystemUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="system-user-form well well-sm well-white">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

        <?php 
        	if(isset($old_password)){
        		echo $form->field($model, 'new_pw')->passwordInput();
        	} else {
        		echo $form->field($model, 'password_hash')->passwordInput();
        	}
        ?>

    <?= $form->field($model, 'type')->dropdownList($type_list, ['prompt'=>'Choose...']) ?>

        <?php 
        	if(isset($status_list)){
        		echo $form->field($model, 'status')->dropdownList($status_list);
        	} 
        ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
