<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Storage;
use backend\models\Stock;
use backend\models\StorageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\yii2GridViewState\widgets\GridView;

// use backend\models\FileUploadForm;
// use yii\web\UploadedFile;
// use yii\helpers\ArrayHelper;


/**
 * StorageController implements the CRUD actions for Storage model.
 */
class StorageController extends Controller
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
     * Lists all Storage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StorageSearch();
		$dataProvider = GridView::search($searchModel);

        $stock_type_list = Stock::GetStockTypeList();

        $storage = Storage::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy('amount asc')->asArray()->all();

        //print_r($storage);die();

        $dataPoints = array();
        $total_value = 0;

        for($i=0; $i< count($storage); $i++){
            $dataPoints[$i]['label'] = $storage[$i]['stock_string'];
            $dataPoints[$i]['amount'] = $storage[$i]['amount'];
            $dataPoints[$i]['price'] = Stock::GetStockPrice($storage[$i]['stock_code']);
            $dataPoints[$i]['sum_of_price'] = ($dataPoints[$i]['amount'] * $dataPoints[$i]['price']);
            $total_value += $dataPoints[$i]['sum_of_price'];
        }

        for($i=0; $i< count($storage); $i++){ //$dataPoints[$i]['amount'] * $dataPoints[$i]['price'] //$total_value
            $dataPoints[$i]['y'] = (($dataPoints[$i]['amount'] * $dataPoints[$i]['price'])/$total_value) *100;
        }

        //print_r($dataPoints);die();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stock_type_list' => $stock_type_list,
            'dataPoints' => $dataPoints,
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
