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

use common\models\Bookmark;

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
        $bookmarked = false;
        $stock = Yii::$app->getRequest()->getQueryParam('Buy');


        if($stock){
            $stock = $stock['code'];
            $search = str_pad($stock,5,'0',STR_PAD_LEFT);

            $bookmark =
                Bookmark::find()
                    ->where(['user_id' => Yii::$app->user->identity->id])
                    ->andWhere(['stock_code' => $search])
                    ->asArray()->one();
            if($bookmark != null) {
                $bookmarked = true;
            }
        }


        return $this->render('index', [
            'model' => $model,
            'search' => $search,
            'bookmarked' => $bookmarked,
        ]);
    }


    public function actionGetbookmark(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = Yii::$app->request->post();

        $stock = $data['stock'];
        $stock = str_pad($stock,5,'0',STR_PAD_LEFT);

        $bookmark =
            Bookmark::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->andWhere(['stock_code' => $stock])
                ->one();

        if($bookmark == null) {
            $model_bookmark = new Bookmark();
            $model_bookmark->user_id = Yii::$app->user->identity->id;
            $model_bookmark->stock_code = $stock;
            $model_bookmark->stock_string = Stock::GetStockInfo($stock)['name'];
            $model_bookmark->save();

            $result = 'success';
        } else {
            $result = 'fail';
            $bookmark->delete();
        }

        $stock_name = Stock::GetStockInfo($stock)['name'];


        return ['result' => $result, 'stock' => $stock, 'stock_name' => $stock_name];
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
