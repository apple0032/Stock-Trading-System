<?php


namespace frontend\components;

use Yii;
use yii\base\Component;

class ECphplib extends Component
{
    public static function genPassword($length)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string

    }

    public static function GetSafeNull($inArray)
    {

        $outArray = array();
        foreach ($inArray as $post_key => $post_value) {
            // echo $post_value." at ".$post_key."<br />";
            if ($post_value == null || $post_value == NULL) {
                if ($post_value == 0) {
                    $outArray[$post_key] = 0;
                } else {
                    $outArray[$post_key] = "";
                }

            } else {
                $outArray[$post_key] = $inArray[$post_key];
            }
            // $outArray[$post_key] = $CONNECTION->real_escape_string($post_value);
            // echo $outArray[$post_key]."123<br />";
        }
        return $outArray;
    }

    public static function GetSafeNull0($inArray)
    {

        $outArray = array();
        foreach ($inArray as $post_key => $post_value) {
            // echo $post_value." at ".$post_key."<br />";
            if ($post_value == null || $post_value == NULL) {
                $outArray[$post_key] = "0";
            } else {
                $outArray[$post_key] = $inArray[$post_key];
            }
            // $outArray[$post_key] = $CONNECTION->real_escape_string($post_value);
            // echo $outArray[$post_key]."123<br />";
        }
        return $outArray;
    }

    public static function GetSafeNull_v2($myArray)
    {
        array_walk_recursive($myArray, function (& $item) {
            if ($item === null) {
                $item = "";
            }
        });
        return $myArray;
    }

    public static function AwsPushNote($endpoint, $default, $message)
    {
        //please install the sdk by composer first
        //Link:http://phptrends.com/dig_in/yii2-aws-sdk
        /* please add the followin to web.php
         *'awssdk' => [
            'class' => 'fedemotta\awssdk\AwsSdk',
                'key' => 'your key',
                'secret' => 'your secret',
            'region' => 'ap-northeast-1', //i.e.: 'us-east-1'
        ],
         **/

        //create a aws object here
        $aws = Yii::$app->awssdk->getAwsSdk();

        //create sns object
        $sns = $aws->get('Sns');
        $return = array();
        /// here is the message
        try {
            $result = $sns->publish([
                'TargetArn' => $endpoint,
                'Message' => json_encode([

                    'default' => $default,
                    'GCM' => json_encode([
                        'data' => [
                            'message' => $message,
                        ]
                    ]),
                ]),
                'MessageStructure' => 'json',
            ]);
            $return['status'] = "Y";
            $return['msg'] = "PUSH_TO_USER";
        } catch (Exception $e) {
            $return['status'] = "E";
            $return['msg'] = "FAIL_TO_USER";
        }


        return $return;


    }

    public static function checkFileExt($file, $extArray)
    {
        foreach ($extArray as $temp) {
            if ($file->extension == $temp) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $variable
     * @return bool
     */
    public static function safeIsNull( $variable )
    {
        if ( ! is_null($variable) && ! empty( $variable ) ) {
            return true;
        } else {
            return false;
        }
    }

}

?>