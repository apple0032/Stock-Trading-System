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
class MarketController extends Controller
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
        $world_index = Stock::GetMarketIndex();

        $index = array();

        for($i=0; $i <15; $i++){
            for($k=0; $k <4; $k++) {
                $index[$i]['code'] = $world_index[$i]->IndexCode;
                $index[$i]['name'] = $world_index[$i]->Name;
                $index[$i]['point'] = $world_index[$i]->Point;
                $index[$i]['difference'] = $world_index[$i]->Difference;
            }
        }

        return $this->render('index', [
            'index' => $index,
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
