<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * ContactForm is the model behind the contact form.
 */
class ProfileForm extends Model {

    public $username;
    public $email;
    public $fio;
    public $tel;
    public $is_individual;
    public $contact;
    public $firm_name;
    public $passport;
    public $inn;
    public $documents_file;
    public $money;
    public $date_consolidator;
    public $user_type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['username', 'email', 'tel'], 'required'],
                ['email', 'email'],
                [['is_individual'], 'integer'],
                [['username', 'fio', 'tel', 'contact', 'firm_name', 'passport', 'inn', 'money', 'date_consolidator', 'user_type'], 'safe'],
                [['documents_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf, zip, rar, tar.gz'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => Yii::t('frontend', 'Username'),
            'email' => Yii::t('frontend', 'Email'),
            'fio' => Yii::t('frontend', 'Fio'),
            'tel' => Yii::t('frontend', 'Tel'),
            'is_individual' => Yii::t('frontend', 'Is Individual'),
            'contact' => Yii::t('frontend', 'Contact'),
            'firm_name' => Yii::t('frontend', 'Firm Name'),
            'passport' => Yii::t('common', 'Passport'),
            'inn' => Yii::t('common', 'INN'),
            'documents_file' => Yii::t('common', 'Documents File'),
        ];
    }

}
