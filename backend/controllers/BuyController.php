<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Buy;
use common\models\Storage;
use backend\models\Stock;
use common\models\History;
use common\models\SystemUser;
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
class BuyController extends Controller
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

            if($this->GetCashBalance($model) == true) {
                $this->History($model);
                $this->Storage($model);
            } else {
                Yii::$app->session->setFlash('error', "資金不足!");
            }
        }


        return $this->render('index', [
            'model' => $model,
            'btype' => $btype,
        ]);
    }

    public function GetCashBalance($model){

        $cost = $model->amount * $model->current_price;

        $cash_balance = SystemUser::find()->select('cash')->where(['id' => Yii::$app->user->identity->id])->one();

        if($cost > $cash_balance->cash){
            return false;
        } else {

            $new_cash = $cash_balance->cash - $cost;

            Yii::$app->db->createCommand() //新現金數值
            ->update('system_user', ['cash' => $new_cash], ['id' => Yii::$app->user->identity->id])
            ->execute();

            return true;
        }

        //print_r($cash_balance->cash);die();
    }

    public function Storage($model){

        $storage =
            Storage::find()
                ->where(['user_id' => Yii::$app->user->identity->id])
                ->andWhere(['stock_code' => $model->code])
                ->asArray()->one();

        if($model->stock_type == null){
            $type = '0'; //正股
        } else {
            $type = '1'; //衍生工具
        }

        if($storage == null) {
            $model_storage = new Storage();
            $model_storage->user_id = Yii::$app->user->identity->id;
            $model_storage->stock_code = $model->code;
            $model_storage->stock_string = $model->name;
            $model_storage->amount = $model->amount;
            $model_storage->type = $type;
            $model_storage->save();
        } else {

            $new_amount = $storage['amount'] + $model->amount;

            Yii::$app->db->createCommand()
                ->update('storage', ['amount' => $new_amount], ['id' => $storage['id']])
                ->execute();
        }

        return $this->redirect(['/storage', 'clear-state' => 1]);
    }

    public function History($model){

        $model_history = new History();
        $model_history->user_id = Yii::$app->user->identity->id;
        $model_history->date = date('Y-m-d');
        $model_history->date_time = date('Y-m-d H:i:s');
        $model_history->stock_code = $model->code;
        $model_history->stock_string = $model->name;
        $model_history->is_buy = 1;
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
