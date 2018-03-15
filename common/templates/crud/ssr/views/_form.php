<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

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
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form well well-sm well-white">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Create') ?> : <?= $generator->generateString('Update') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
