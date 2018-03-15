<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Buy;
use common\models\Storage;
use backend\models\Stock;
use common\models\History;
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
class SearchController extends Controller
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
        $model = new Buy();

        $search = null;
        $stock = Yii::$app->getRequest()->getQueryParam('Buy');
        if($stock){
            $stock = $stock['code'];
            $search = str_pad($stock,5,'0',STR_PAD_LEFT);
        }


        return $this->render('index', [
            'model' => $model,
            'search' => $search,
        ]);
    }





    protected function findModel($id)
    {
        if (($model = Storage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
