<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".sidebar-toggle").click(function(){
            $('.main-sidebar').css("display", "block");
            $(".skin-blue").toggleClass("sidebar-collapse");
            $(".skin-blue").toggleClass("sidebar-open");
        });
    });
</script>
<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

// use Yii;
use common\components\Functions;

if (Yii::$app->controller->action->id === 'login') {
/**
 * Do not use this code in your template. Remove it.
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

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

    $pagePermission = json_decode(Yii::$app->request->cookies->getValue('permission'), true);
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
    <body class="skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            [
                'directoryAsset' => $directoryAsset,
                'pagePermission' => $pagePermission,
            ]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
