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
/* @var $model common\models\Storage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="storage-form well well-sm well-white">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'stock_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
