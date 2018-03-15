<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;


$this->title = '查詢證券';
//$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .search-index{
        padding: 30px;
        box-shadow: 0 6px 12px rgba(0,0,0,.175);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        border-top: 6px solid #79c6c9;
    }

</style>
<?php
$js = <<<JS

    $(".sidebar-toggle").click(function(){
        $('.main-sidebar').css("display", "block");
        $(".skin-blue").toggleClass("sidebar-collapse");
        $(".skin-blue").toggleClass("sidebar-open");
    });


JS;
$this->registerJs($js, \yii\web\View::POS_READY);
?>

<div class="search-index well well-sm well-white">

    <?php
    $form = ActiveForm::begin([
        'id' => 'form-daily',
        'type' => ActiveForm::TYPE_INLINE,
        'method' =>  'get',
    ]);
    ?>
    <?= $form->field($model, 'code') ?>

    <?= Html::submitButton('查詢證券', ['class' => 'btn btn-success test']) ?>

    <?php ActiveForm::end(); ?>



    <?php if($search != null) {  ?>

        <iframe src="http://services1.aastocks.com/Web/CHSU/Quote.aspx?language=chi&platform=desktop&symbol=<?php echo $search ?>" style="width:100%; height:650px; border: 1px solid #ccc; padding:10px;"></iframe>

    <?php }?>

</div>




