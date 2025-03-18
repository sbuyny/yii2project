<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%licence_log}}".
 *
 * @property integer $id
 * @property integer $licence_id
 * @property integer $broker_id
 * @property integer $owner_id
 * @property integer $procent
 * @property string $licence_number
 * @property string $documents_file
 * @property integer $date_register
 * @property integer $date_start
 * @property integer $date_finish
 * @property integer $date_changed
 */
class LicenceLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%licence_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['licence_id', 'broker_id', 'owner_id', 'procent', 'date_register', 'date_start', 'date_finish','date_changed'], 'integer'],
            [['licence_number', 'documents_file'], 'string', 'max' => 255],
            [['broker_id','owner_id','date_register','date_changed'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'licence_id' => Yii::t('common', 'Owner'),
            'broker_id' => Yii::t('common', 'Broker'),
            'owner_id' => Yii::t('common', 'Owner'),
            'procent' => Yii::t('common', 'Licence Procent'),
            'price' => Yii::t('common', 'Licence Price'),
            'licence_number' => Yii::t('common', 'Licence Number'),
            'documents_file' => Yii::t('common', 'Documents File'),
            'date_register' => Yii::t('common', 'Date Register'),
            'date_start' => Yii::t('common', 'Date Start'),
            'date_finish' => Yii::t('common', 'Date Finish'),
            'date_changed' => Yii::t('common', 'Date'),
            'description' => Yii::t('common', 'Description'),
        ];
    }
}
