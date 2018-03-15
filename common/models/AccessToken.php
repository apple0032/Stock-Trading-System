<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "access_token".
 *
 * @property integer $id
 * @property string $access_token
 * @property string $remote_ip
 * @property integer $user_id
 * @property string $data_json
 * @property string $version
 * @property string $device_type
 * @property integer $create_user
 * @property string $create_date
 * @property integer $update_user
 * @property string $update_date
 */
class AccessToken extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'create_user', 'update_user'], 'integer'],
            [['data_json'], 'string'],
            [['version', 'device_type'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['access_token'], 'string', 'max' => 127],
            [['remote_ip'], 'string', 'max' => 64],
            [['version', 'device_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'access_token' => 'Access Token',
            'remote_ip' => 'Remote Ip',
            'user_id' => 'User ID',
            'data_json' => 'Data Json',
            'version' => 'Version',
            'device_type' => 'Device Type',
            'create_user' => 'Create User',
            'create_date' => 'Create Date',
            'update_user' => 'Update User',
            'update_date' => 'Update Date',
        ];
    }


    public static function generateAccessToken()
    {
        return Yii::$app->security->generateRandomString();
    }

    public static function findToken($tokenStr)
    {
        return AccessToken::find()
            ->where(['access_token' => $tokenStr])
            ->one();
    }


    public function validateLifetime()
    {
        if (time() - strtotime($this->update_date) > Yii::$app->params['TOKEN_LIFETIME']) {
            $this->delete();
            return false;
        } else {
            return true;
        }
    }

    public function validateuser($user_id)
    {
        if ($this->user_id == $user_id) {
            return $user_id;
        } else {
            return -1;
        }
    }

    public function singletoken()
    {
        $query = AccessToken::find()->where(["user_id" => $this->user_id])->andWhere(["<>", "user_id", 0])->orderBy("id desc");
        if ($query->count() > 1) {
            $temp_token = $query->all();//->orderBy('id DESC')
            $i = 0;
            foreach ($temp_token as $temp) {
                if (0 < $i) {
                    $temp->delete();
                    $i++;
                } else {
                    $i++;
//                    break;
                }

            }
            return false;
        } else {
            return true;
        }
    }

    public function refresh_token()
    {
        $this->update_date = date('Y-m-d H:i:s');
        $this->save();
    }

    public static function validatetoken($token, $user_id, $isvaliduser, $isvalidlifetime, $token_single = false)
    {
        $status = array();
        $status["status"] = "Y";
        $status["debug_msg"] = "DONE";
        $accesstoken = AccessToken::findToken($token);

        //delete token
        $deltoken = AccessToken::find()->where(['<', 'update_date', date("Y-m-d H:i:s", strtotime('yesterday'))]);
        foreach ($deltoken->all() as $temp) {
            $temp->delete();
        }

        if ($accesstoken == null) {
            $status['status'] = "E";
            $status["debug_msg"] = Yii::$app->params['ERROR_MSG']["INVLIAD_TOKEN"];
            return $status;
        }
        $status['user_id'] = $user_id;
//        $status['user_type'] = $accesstoken->user_type;
        if ($isvaliduser == true) {
            $isvalid_result = $accesstoken->validateuser($user_id);
            if ($isvalid_result == -1) {
                $status['status'] = "E";
                $status["debug_msg"] = Yii::$app->params['ERROR_MSG']["NO_THIS_USER"];
                return $status;
            }
        }

        if ($isvalidlifetime == true) {
            $lifetime_result = $accesstoken->validateLifetime();
            if ($lifetime_result == false) {
                $status['status'] = "E";
                $status["debug_msg"] = Yii::$app->params['ERROR_MSG']["T_EXP"];
                return $status;
            }
        }
        if ($token_single == true) {
            if (!$accesstoken->singletoken()) {
                $status['status'] = "E";
                $status["debug_msg"] = Yii::$app->params['ERROR_MSG']["SIN_TOKEN"];
                unset($status['user_id']);
                return $status;
            }
        }


        $accesstoken->refresh_token();
        return $status;
    }
}
