<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\Stock;
use common\models\SystemUser;
use common\models\Storage;
use common\models\History;

use yii\filters\VerbFilter;



class PageController extends Controller
{

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



    public function actionIndex(  )
    {

        return $this->render('index', [

        ]);
    }

    public function actionHome()
    {
        //用戶現金儲備
        $cash_balance = SystemUser::find()->select('cash')->where(['id' => Yii::$app->user->identity->id])->one();
        $cash_balance = $cash_balance->cash;

        //用戶持倉總市值
        $storage_total = 0;
        $storage_all = Storage::find()->where(['user_id' => Yii::$app->user->identity->id])->asArray()->all();

        for($i=0; $i< count($storage_all); $i++){
            $stock_price = Stock::GetStockPrice($storage_all[$i]['stock_code']);
            $storage_total = $storage_total + $storage_all[$i]['amount'] * $stock_price;
        }


        //用戶股票總市價
        $storage = Storage::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere(['type' => 0])->orderBy('amount asc')->asArray()->all();

        $storage_of_stock = 0;

        for($i=0; $i< count($storage); $i++){
            $stock_price = Stock::GetStockPrice($storage[$i]['stock_code']);
            $storage_of_stock = $storage_of_stock + $storage[$i]['amount'] * $stock_price;
        }

        //用戶衍生工具總市值
        $storage_of_der = $storage_total - $storage_of_stock;


        //用戶加入日期
        $join_day = SystemUser::find()->select('create_date')->where(['id' => Yii::$app->user->identity->id])->one();
        $join_day = $join_day->create_date;


        //最近一次交易
        $trade = History::find()->select('date')->where(['user_id' => Yii::$app->user->identity->id])->orderBy('amount asc')->one();
        $trade = $trade->date;


        return $this->render('home', [
            'cash_balance' => $cash_balance,
            'storage_total' => $storage_total,
            'storage_of_stock' => $storage_of_stock,
            'storage_of_der' => $storage_of_der,
            'join_day' => $join_day,
            'trade' => $trade,
        ]);
    }

}
