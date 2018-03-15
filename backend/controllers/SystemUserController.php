<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\SystemUser;
use backend\models\SystemUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\yii2GridViewState\widgets\GridView;

// use backend\models\FileUploadForm;
// use yii\web\UploadedFile;
// use yii\helpers\ArrayHelper;


/**
 * SystemUserController implements the CRUD actions for SystemUser model.
 */
class SystemUserController extends Controller
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
     * Lists all SystemUser models.
     * @return mixed
     */
    public function actionIndex()
    {
    	if(Yii::$app->user->identity->type !== 'admin'){
    		return $this->goHome();
    	}

        $searchModel = new SystemUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$type_list = SystemUser::getListType();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type_list' => $type_list,
        ]);
    }

    /**
     * Displays a single SystemUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if(Yii::$app->user->identity->type !== 'admin'){
    		return $this->goHome();
    	}

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
     * Creates a new SystemUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
    	if(Yii::$app->user->identity->type !== 'admin'){
    		return $this->goHome();
    	}

        $model = new SystemUser();

        $type_list = SystemUser::getListType();

        if ($model->load(Yii::$app->request->post()) ) {
		
        if (($model->type == 'van') || ($model->type == 'warehouse')) {
            $model->password_hash = md5($model->password_hash);
        } else {
		$model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password_hash);
        }

		$model->create_user = Yii::$app->user->identity->id;
		$model->create_date = date('Y-m-d H:i:s');
		$model->status = 1;
		$model->superuser = 1;

			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
                $model->password_hash = '';
            }
        } else {
		
		}
		
		
		return $this->render('create', [
			'model' => $model,
			'type_list' => $type_list,
		]);
    }

    /**
     * Updates an existing SystemUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
    	if(Yii::$app->user->identity->type !== 'admin'){
    		return $this->goHome();
    	}

        $model = $this->findModel($id);
		
		$type_list = SystemUser::getListType();
        $status_list = ['1' => 'active',
                      '0' => 'disable',
                    ];


		$old_password = $model->password_hash;
	
        if ($model->load(Yii::$app->request->post()) ) {

        	if($model->new_pw != ''){
                if (($model->type == 'van') || ($model->type == 'warehouse')) {
                    $model->password_hash = md5($model->new_pw);
                } else {
                    $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->new_pw);
                }
        	} else {
        		$model->password_hash = $old_password;
        	}

        	$model->update_user = Yii::$app->user->identity->id;
			$model->update_date = date('Y-m-d H:i:s');

			if ($model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			}
        }
		return $this->render('update', [
			'model' => $model,
			'old_password' => $old_password,
			'type_list' => $type_list,
			'status_list' => $status_list,
		]);
    }

    /**
     * Deletes an existing SystemUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	if(Yii::$app->user->identity->type !== 'admin'){
    		return $this->goHome();
    	}

        $model = $this->findModel($id);
		$model->status = 0;
		$model->save();

		//$model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SystemUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SystemUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SystemUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
