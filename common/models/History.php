<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $date_time
 * @property string $stock_code
 * @property string $stock_string
 * @property integer $is_buy
 * @property integer $amount
 * @property double $price
 * @property integer $status
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'date', 'date_time', 'stock_code', 'stock_string', 'is_buy', 'amount', 'price', 'status' ,'trade_status'], 'required'],
            [['user_id', 'is_buy', 'amount', 'status','trade_status'], 'integer'],
            [['date', 'date_time'], 'safe'],
            [['price'], 'number'],
            [['stock_code', 'stock_string'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'date' => '交易日期',
            'date_time' => '交易日期',
            'stock_code' => '證券號碼',
            'stock_string' => '	證券名稱',
            'is_buy' => '買入/賣出',
            'amount' => '總數量',
            'price' => '買入/賣出價',
            'status' => '狀態',
            'trade_status' => 'trade_status',
        ];
    }
}
