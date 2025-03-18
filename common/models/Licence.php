<?php

namespace common\models;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%licence}}".
 *
 * @property integer $id
 * @property integer $broker_id
 * @property integer $owner_id
 * @property integer $procent
 * @property string $licence_number
 * @property string $documents_file
 * @property integer $date_register
 * @property integer $date_start
 * @property integer $date_finish
 */
class Licence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%licence}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['broker_id', 'owner_id', 'procent', 'date_register', 'date_start', 'date_finish'], 'integer'],
            [['licence_number', 'documents_file'], 'string', 'max' => 255],
            [['broker_id','owner_id','date_register'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'broker_id' => Yii::t('common', 'Broker'),
            'owner_id' => Yii::t('common', 'Owner'),
            'procent' => Yii::t('common', 'Licence Procent'),
            'price' => Yii::t('common', 'Licence Price'),
            'licence_number' => Yii::t('common', 'Licence Number'),
            'documents_file' => Yii::t('common', 'Documents File'),
            'date_register' => Yii::t('common', 'Date Register'),
            'date_start' => Yii::t('common', 'Date Start'),
            'date_finish' => Yii::t('common', 'Date Finish'),
        ];
    }
    
    /**
     * Get Licence list.
     *
     * @return array
     */
    public static function getLicenceList() {
        $licence =  Licence::find()
                ->select(['id', 'licence_number'])
                ->where([
                    'broker_id' => 0,
                    'owner_id' => Yii::$app->user->identity->id,
                    ])
                ->all();

        return ArrayHelper::map($licence, 'id', 'licence_number');
    }
}
