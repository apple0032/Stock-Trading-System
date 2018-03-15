<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use yii\filters\AccessControl;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\yii2GridViewState\widgets\GridView;

// use backend\models\FileUploadForm;
// use yii\web\UploadedFile;
// use yii\helpers\ArrayHelper;


/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
				],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
		$dataProvider = GridView::search($searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);
		

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
		
		/* 
		// ========== start file upload =====
		$file_upload_form = [];
		foreach ( ['file_pic_en', 'file_pic_zh'] as $temp_name ) {
			$file_upload_form[$temp_name] = new FileUploadForm();
			$file_upload_form[$temp_name]->type = 'image';
			$file_upload_form[$temp_name]->upload_folder_name = FileUploadForm::FOLDER_ADBANNER;
			
		}
		
		foreach ( $file_upload_form as $temp_name => $file_upload_form1 ) {
			if ( $model->$temp_name ) {
				$model->$temp_name = \Yii::getAlias('@kcfinderupload') . '/' . $file_upload_form1->upload_folder_name  . '/' . $model->$temp_name;
			} else {
				$model->$temp_name = null;
			}
		}
		// ========== end file upload =====
		*/
		
		return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();

		// $dropdownfield_list = ArrayHelper::map(\common\models\MyModel::find()->asArray()->all(), 'id', 'name');
		
		
		/*
		// ========== start file upload =====
		$file_upload_form = [];
		foreach ( ['file_pic_en', 'file_pic_zh'] as $temp_name ) {
			$file_upload_form[$temp_name] = new FileUploadForm();
			$file_upload_form[$temp_name]->type = 'image';
			$file_upload_form[$temp_name]->upload_folder_name = FileUploadForm::FOLDER_ADBANNER;
		}
		// ========== end file upload =====
		*/
				
        if ($model->load(Yii::$app->request->post()) ) {
			// $model->create_date = date('Y-m-d H:i:s');
			// $model->update_date = date('Y-m-d H:i:s');
			
			/*
			// ========== start file upload =====
			$upload_results = FileUploadForm::uploadAll( $file_upload_form );

			if ( $upload_results === false ) {
				// $upload_errors = FileUploadForm::$all_errors;
			} else {
				foreach ($upload_results as $temp_name => $temp_value) {
					$model->$temp_name = $temp_value;
				}
			}
			
			foreach ( array_keys($file_upload_form) as $temp_name ) {
				$model->$temp_name = basename($model->$temp_name);
			}
			// ========== end file upload =====
			*/
			
			if ($model->save()) {
				return $this->redirect(['view', <?= $urlParams ?>]);
			}
        } else {
			// set default value
			// $model->type = 0;
			// $model->display = 1;
		}
		
		/*
		// ========== start file upload =====
		foreach ( $file_upload_form as $temp_name => $file_upload_form1 ) {
			if ($model->$temp_name) {
				$model->$temp_name = \Yii::getAlias('@kcfinderupload') . '/' . $file_upload_form1->upload_folder_name  . '/' . $model->$temp_name;
			} else {
				$model->$temp_name = null;
			}
		}
		// ========== end file upload =====
		*/
		
		return $this->render('create', [
			'model' => $model,
			// 'dropdownfield_list' => $dropdownfield_list,
		]);
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
		
		/*
		// ========== start file upload =====
		$file_upload_form = [];
		foreach ( ['file_pic_en', 'file_pic_zh'] as $temp_name ) {
			$file_upload_form[$temp_name] = new FileUploadForm();
			$file_upload_form[$temp_name]->type = 'image';
			$file_upload_form[$temp_name]->upload_folder_name = FileUploadForm::FOLDER_ADBANNER;
		}
		// ========== end file upload =====
		*/
		

        if ($model->load(Yii::$app->request->post()) ) {
			// $model->update_date = date('Y-m-d H:i:s');
			
			/*
			// ========== start file upload =====
			$upload_results = FileUploadForm::uploadAll( $file_upload_form );

			if ( $upload_results === false ) {
				// $upload_errors = FileUploadForm::$all_errors;
			} else {
				foreach ($upload_results as $temp_name => $temp_value) {
					$model->$temp_name = $temp_value;
				}
			}
			
			foreach ( array_keys($file_upload_form) as $temp_name ) {
				$model->$temp_name = basename($model->$temp_name);
			}
			// ========== end file upload =====
			*/
			
			
			if ($model->save()) {
				return $this->redirect(['view', <?= $urlParams ?>]);
			}
        }
		return $this->render('update', [
			'model' => $model,
		]);
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
		$model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
