<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bookmark".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $stock_code
 * @property string $stock_string
 */
class Bookmark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookmark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'stock_code', 'stock_string'], 'required'],
            [['user_id'], 'integer'],
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
            'stock_code' => '證券號碼',
            'stock_string' => '證券名稱',
        ];
    }
}
