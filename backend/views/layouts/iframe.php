<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    
    $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', ['depends' => [dmstr\web\AdminLteAsset::className()],]);
    $this->registerJsFile(Yii::$app->request->baseUrl.'/plugins/daterangepicker/daterangepicker.js', ['depends' => [dmstr\web\AdminLteAsset::className()],]);
    $this->registerJsFile(Yii::$app->request->baseUrl.'/plugins/datepicker/bootstrap-datepicker.js', ['depends' => [dmstr\web\AdminLteAsset::className()],]);
    $this->registerJsFile(Yii::$app->request->baseUrl.'/js/backend.js', ['depends' => [dmstr\web\AdminLteAsset::className()],]);
    
    $this->registerCssFile(Yii::$app->request->baseUrl.'/plugins/datepicker/datepicker3.css', [
        'depends' => [dmstr\web\AdminLteAsset::className()],
//        'media' => 'print',
        ], 'datepicker3'
    );
    $this->registerCssFile(Yii::$app->request->baseUrl.'/plugins/daterangepicker/daterangepicker-bs3.css', [
        'depends' => [dmstr\web\AdminLteAsset::className()],
//        'media' => 'print',
        ], 'daterangepicker-bs3'
    );
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="iframe-page">

<?php $this->beginBody() ?>

    <?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
