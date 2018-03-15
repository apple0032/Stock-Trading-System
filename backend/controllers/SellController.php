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
use common\models\SystemUser;
use common\components\yii2GridViewState\widgets\GridView;

// use backend\models\FileUploadForm;
// use yii\web\UploadedFile;
// use yii\helpers\ArrayHelper;


/**
 * SystemUserController implements the CRUD actions for SystemUser model.
 */
class SellController extends Controller
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
        $btype = ['0' => '市價盤'];
        $model->btype = 0;

//        $xx = Stock::GetStockPrice('00013');
//        print_r($xx);die();

        if ($model->load(Yii::$app->request->post()) ) {

            if($this->Storage($model) != true){
                Yii::$app->session->setFlash('error', "你輸入的資料不乎或並沒有持有以下之證券。");
            } else {
                $this->GetCashBalance($model);
                $this->History($model);

                Yii::$app->session->setFlash('success', "完成交易");
                return $this->redirect(['/storage', 'clear-state' => 1]);
            }

        }

        $storage = Storage::find()->where(['user_id' => Yii::$app->user->identity->id])->orderBy('amount asc')->asArray()->all();

        $dataPoints = array();
        $total_value = 0;

        for($i=0; $i< count($storage); $i++){
            $dataPoints[$i]['code'] = $storage[$i]['stock_code'];
            $dataPoints[$i]['label'] = $storage[$i]['stock_string'];
            $dataPoints[$i]['amount'] = $storage[$i]['amount'];
            $dataPoints[$i]['price'] = Stock::GetStockPrice($storage[$i]['stock_code']);
            $dataPoints[$i]['sum_of_price'] = ($dataPoints[$i]['amount'] * $dataPoints[$i]['price']);
            $total_value += $dataPoints[$i]['sum_of_price'];
        }

        $storage = $dataPoints;

        return $this->render('index', [
            'model' => $model,
            'btype' => $btype,
            'storage' => $storage,
        ]);
    }

    public function GetCashBalance($model){

        $cost = $model->amount * $model->current_price;

        $cash_balance = SystemUser::find()->select('cash')->where(['id' => Yii::$app->user->identity->id])->one();

        $new_cash = $cash_balance->cash + $cost;

        Yii::$app->db->createCommand() //新現金數值
        ->update('system_user', ['cash' => $new_cash], ['id' => Yii::$app->user->identity->id])
            ->execute();

        return true;

    }

    public function Storage($model){

        $model_storage =
            Storage::find()
                ->where(['stock_code' => $model->code])
                ->andWhere(['user_id' => Yii::$app->user->identity->id])
                ->one();

        if($model_storage == null){  //沒有持倉紀錄
            return false;
        } else {

            if($model->amount > $model_storage->amount){  //如輸入股數大過持倉數目
                return false;
            }

            $new_amount = $model_storage->amount - $model->amount;

            Yii::$app->db->createCommand() //更改股數
                ->update('storage', ['amount' => $new_amount], ['id' => $model_storage->id])
                ->execute();

            Yii::$app->db->createCommand() //沽出部份股份->不計算平均買入價
                ->update('storage', ['had_sell' => 1], ['id' => $model_storage->id])
                ->execute();

            if($model->amount == $model_storage->amount){   //全股沽清
                $model_storage->delete();
            }

            return true;
        }

    }

    public function History($model){

        $model_history = new History();
        $model_history->user_id = Yii::$app->user->identity->id;
        $model_history->date = date('Y-m-d');
        $model_history->date_time = date('Y-m-d H:i:s');
        $model_history->stock_code = $model->code;
        $model_history->stock_string = $model->name;
        $model_history->is_buy = 0;
        $model_history->amount = $model->amount;
        $model_history->price = $model->current_price;
        $model_history->trade_status = 1;
        $model_history->status = 1;
        $model_history->save();

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


    public function actionView($id)
    {

        $model = $this->findModel($id);

		
		return $this->render('view', [
            'model' => $model,
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
