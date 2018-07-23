<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Storage;
use common\models\Bookmark;
use common\models\Add;
use backend\models\Stock;
use backend\models\StorageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\yii2GridViewState\widgets\GridView;
use backend\models\CollectSearch;

// use backend\models\FileUploadForm;
// use yii\web\UploadedFile;
// use yii\helpers\ArrayHelper;


/**
 * StorageController implements the CRUD actions for Storage model.
 */
class CollectController extends Controller
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
        $model = new Add();

        $searchModel = new CollectSearch();
		$dataProvider = GridView::search($searchModel);

        $stock_type_list = Stock::GetStockTypeList();

        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stock_type_list' => $stock_type_list,
            'model' => $model,
        ]);
    }


    public function actionGetstockinfo(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $stock = $data['stock'];
        $stock = str_pad($stock,5,'0',STR_PAD_LEFT);

        $price = Stock::GetStockPrice($stock);
        $name = Stock::GetStockInfo($stock)['name'];
        $lotsize = Stock::GetStockInfo($stock)['lotsize'];
        $uaCode = Stock::GetStockInfo($stock)['uaCode'];


        return ['stock' => $stock ,'price' => $price, 'name' => $name, 'lotsize' => $lotsize, 'uaCode' => $uaCode];
    }

    public function actionGetbookmark(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $stock = $data['stock'];
        $stock = str_pad($stock,5,'0',STR_PAD_LEFT);

        $price = Stock::GetStockPrice($stock);

        $bookmark =
            Bookmark::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->andWhere(['stock_code' => $stock])
                ->one();

        $db_id = null;

        if($bookmark == null) {
            $model_bookmark = new Bookmark();
            $model_bookmark->user_id = Yii::$app->user->identity->id;
            $model_bookmark->stock_code = $stock;
            $model_bookmark->stock_string = Stock::GetStockInfo($stock)['name'];
            $model_bookmark->save();

            $db_id = $model_bookmark->getPrimaryKey();

            $result = 'success';
        } else {
            $result = 'fail';
        }

        $stock_name = Stock::GetStockInfo($stock)['name'];


        return ['db_id' => $db_id,'result' => $result, 'stock' => $stock, 'stock_name' => $stock_name, 'price' => $price];
    }

    public function actionDelbookmark(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $id = $data['id'];

        $bookmark =
            Bookmark::find()
                ->where(['id' => $id])
                ->one();

        if($bookmark != null) {
            $result = 'deleted';
            $bookmark->delete();
        } else {
            $result = 'error';
        }


        return ['result' => $result, 'id' => $id];
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
