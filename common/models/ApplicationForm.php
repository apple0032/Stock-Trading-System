<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 26/8/2016
 * Time: 17:16
 *
 * @property $name
 * @property $email
 * @property $contact_no
 * @property $post
 * @property $cv
 */

namespace common\models;


use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class ApplicationForm extends ActiveRecord
{
    public static function tableName()
    {
        return 'application';
    }
    /**
     * @var UploadedFile
     */
    public $cv;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // All required
            [['name', 'email', 'contact_no', 'post', 'cv'], 'required'],
            [['name', 'post'], 'string'],
            [['email'], 'email'],
            // Filter the space and hyphen, plus sign is kept for indicating country code
            [['phone'], 'filter', 'filter' => function($value) {
                return preg_replace("/\s|-/g", '', $value);
            }],
            [['cv'], 'file', 'skipOnEmpty' => false, 'extensions' => ['pdf'], 'maxSize' => 1024 * 8192]
        ];
    }


    public function getAttribute($name)
    {
        return [
            'name'          =>  'Name',
            'email'         =>  'Email',
            'contact_no'    =>  'Contact Number',
            'post'          =>  'Post',
            'cv'            =>  'CV',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->cv->saveAs('uploads/' . $this->cv->baseName . '.' . $this->cv->extension);
            return true;
        } else {
            return false;
        }
    }
}