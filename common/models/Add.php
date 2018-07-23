<?php

namespace common\models;
use yii\base\Model;

use Yii;

class Add extends Model
{

    public $code;
    public $name;
    public $current_price;
    public $type;

    public function rules()
    {
        return [
            [['code'], 'required', 'message' => '請輸入 {attribute}'],
            [['name','current_price'], 'string'],
            [['code'],'number', 'message' => '請輸入數字'],
            [['type'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code' => '證券號碼',
            'name' => '證券名稱',
            'current_price' => '證券現價',
            'type' => '證券類型',
        ];
    }
}
