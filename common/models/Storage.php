<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "storage".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $stock_code
 * @property integer $amount
 */

class Storage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'storage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'stock_code', 'amount','type'], 'required'],
            [['user_id', 'amount','had_sell'], 'integer'],
            [['stock_code','stock_string'], 'string', 'max' => 255],
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
            'stock_code' => '證券號碼',
            'stock_string' => '證券名稱',
            'amount' => '持貨量',
            'type' => '證券類型',
            'had_sell' => '曾沽出',
        ];
    }
}
