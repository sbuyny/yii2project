<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%licence_price}}".
 *
 * @property integer $id
 * @property integer $minimal_number_licences
 * @property integer $maximum_number_licences
 * @property integer $price
 */
class LicencePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%licence_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minimal_number_licences', 'maximum_number_licences', 'price'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'minimal_number_licences' => Yii::t('backend', 'Minimal Number Licences'),
            'maximum_number_licences' => Yii::t('backend', 'Maximum Number Licences'),
            'price' => Yii::t('backend', 'Price'),
        ];
    }
}
