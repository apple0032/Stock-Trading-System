<?php

namespace common\models;
use yii\base\Model;

use Yii;

class Buy extends Model
{

    public $code;
    public $name;
    public $current_price;
    public $lotsize;
    public $amount;
    public $total;
    public $btype;
    public $stock_type;

    public function rules()
    {
        return [
            [['code','amount'], 'required', 'message' => '請輸入 {attribute}'],
            [['name','current_price'], 'string'],
            [['code','lotsize'],'number', 'message' => '請輸入數字'],
            [['amount'], 'integer', 'message' => '請以正整數輸入 {attribute}'],
            [['total','btype'], 'integer'],
            [['btype'], 'required', 'message' => '請選擇 {attribute}'],
            [['stock_type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '證券號碼',
            'name' => '證券名稱',
            'current_price' => '證券現價',
            'lotsize' => '每手數目',
            'amount' => '落盤股數',
            'total' => '總金額',
            'btype' => '下盤類型',
            'stock_type' => '證券類型',
        ];
    }
}
