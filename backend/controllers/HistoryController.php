<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\History;
use backend\models\HistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\yii2GridViewState\widgets\GridView;

// use backend\models\FileUploadForm;
// use yii\web\UploadedFile;
// use yii\helpers\ArrayHelper;


/**
 * HistoryController implements the CRUD actions for History model.
 */
class HistoryController extends Controller
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
     * Lists all History models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HistorySearch();
		$dataProvider = GridView::search($searchModel);
		$boolean_list = ['1' => '買入', '2' => '賣出'];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'boolean_list' => $boolean_list,
        ]);
    }

    /**
     * Displays a single History model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
		
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
     * Creates a new History model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new History();

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
				return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing History model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
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
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }
		return $this->render('update', [
			'model' => $model,
		]);
    }

    /**
     * Deletes an existing History model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the History model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return History the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = History::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
