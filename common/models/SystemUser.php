<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "system_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $type
 * @property integer $create_user
 * @property string $create_date
 * @property integer $update_user
 * @property string $update_date
 */
class SystemUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $new_pw;

    public static function tableName()
    {
        return 'system_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'unique', 'targetClass' => '\common\models\SystemUser', 'message' => 'This username has already been taken.'],
            [['username', 'password_hash', 'type'], 'required'],
            [['password_hash'], 'string'],
            [['status','create_user', 'update_user','cash'], 'integer'],
            [['new_pw','create_date', 'update_date'], 'safe'],
            [['username'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'type' => '類型',
            'create_user' => 'Create User',
            'create_date' => 'Create Date',
            'update_user' => 'Update User',
            'update_date' => 'Update Date',
            'password_hash' => '密碼',
            'new_pw' => '新密碼',
            'status' => '狀態',
            'cash' => '現金',
        ];
    }

    public static function getListType() {
        $type_list = [
                      'cs' => 'cs',
                      'admin' => 'admin',
                    ];
        return $type_list;
    }

}
