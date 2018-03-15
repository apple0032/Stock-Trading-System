<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Admin;
use common\models\VirtualAdmin;
use backend\models\AdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\components\Functions;
use common\models\Status;

use common\models\Department;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends Controller
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
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {

        if(isset(Yii::$app->user->identity->superuser) == false && Yii::$app->user->identity->superuser != 1) {
            return $this->renderContent("You not have the permission.");
        }

        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Admin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // echo '<pre>';
        // var_dump(Functions::getPagePermission(Yii::$app->user->identity->departments));
        // die();

        if(isset(Yii::$app->user->identity->superuser) == false && Yii::$app->user->identity->superuser != 1) {
            return $this->renderContent("You not have the permission.");
        }

        $model = $this->findModel($id);

        $uaer_dp = json_decode($model->departments);
        $dp_list = Department::getList();

        $dp_str = '';

        if(!is_null($uaer_dp) && !empty($uaer_dp)) {
            foreach($dp_list as $dp_id => $dp) {
                foreach ($uaer_dp as $key => $value) {
                    # code...
                    if($dp_id == $key) {
                        $dp_str .= $dp . ', ';
                    }
                }
            }
        }

        $model->departments = $dp_str;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(isset(Yii::$app->user->identity->superuser) == false && Yii::$app->user->identity->superuser != 1) {
    return $this->renderContent("You not have the permission.");
}

        $model = new VirtualAdmin();
        $status_list = Status::getList();
        $department_list = Department::getList();

        if ($model->load(Yii::$app->request->post())) {

            // if(!empty(Yii::$app->request->post('departments')) && !is_null(Yii::$app->request->post('departments'))) {
                $model->departments = json_encode(Yii::$app->request->post('departments'));
            // } else {
            //     $model->departments = '';
            // }

            if($model->custom_save()) {
                return $this->redirect(['admin/index', 'clear-state' => 1]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'status_list' => $status_list,
            'department_list' => $department_list,
        ]);
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(isset(Yii::$app->user->identity->superuser) == false && Yii::$app->user->identity->superuser != 1) {
    return $this->renderContent("You not have the permission.");
}

        $model = $this->findModel($id);
        $status_list = Status::getList();
        $department_list = Department::getList();

        if ($model->load(Yii::$app->request->post())) {
            // if(!empty(Yii::$app->request->post('departments')) && !is_null(Yii::$app->request->post('departments'))) {
                $model->departments = json_encode(Yii::$app->request->post('departments'));
            // } else {
            //     $model->departments = '';
            // }

            if($model->custom_save()) {
                return $this->redirect(['admin/index', 'clear-state' => 1]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'status_list' => $status_list,
            'department_list' => $department_list,
        ]);
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(isset(Yii::$app->user->identity->superuser) == false && Yii::$app->user->identity->superuser != 1) {
    return $this->renderContent("You not have the permission.");
}

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VirtualAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
