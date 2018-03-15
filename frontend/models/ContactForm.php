<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends \common\models\Contact
{
    public $subject;
    public $body;
    public $country;
    public $title;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $verifyCode;
    public $date_create;
    public $upload;
    public $upload_path;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['email', 'first_name', 'phone'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
             [['upload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif, zip, 7z, rar, tgz, gz, pdf, doc, docx, xls, xlsx, ppt, pptx, ods, odt, rtf, htm, html, txt, exe, apk, PNG, JPG, GIF, ZIP, 7Z, RAR, TGZ, GZ, PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ODS, ODT, RTF, HTM, HTML, TXT, EXE, APK', 'maxFiles' => 1],             
            ['body', 'safe'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

            public function uploadFile()
        {
            $this->upload_path = '';
            if ($this->validate()) {
                $this->upload_path = \Yii::getAlias('@runtime/cache/' . $this->upload->baseName . '.' . $this->upload->extension);
                $this->upload->saveAs($this->upload_path);
                return true;
            } else {
                return false;
            }
        }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('frontend/contact', 'subject'),
            'body' => Yii::t('frontend/contact', 'body'),
            'country' => Yii::t('frontend/contact', 'country'),
            
            'title' => Yii::t('frontend/contact', 'title'),
            'last_name' => Yii::t('frontend/contact', 'last_name'),
            'first_name' => Yii::t('frontend/contact', 'first_name'),
            'phone' => Yii::t('frontend/contact', 'phone'),
            'email' => Yii::t('frontend/contact', 'email'),
            
            'verifyCode' => Yii::t('frontend/contact', 'verifyCode'),
        ];
    }


    public function create()
    {
        //if ( !$this->validate() ) {
        //    return false;
        //}
        $model = new \common\models\Contact;
        $this->date_create = date('Y-m-d H:i:s');
        foreach ( $model->attributes as $a => $v) {
            $model->$a = $this->$a;
        }
        return $model->save();
    }
    
    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
