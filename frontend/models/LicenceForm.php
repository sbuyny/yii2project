<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * LicenceForm is the model behind the buy licences form.
 */
class LicenceForm extends Model {

    public $number_licences;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['number_licences'], 'required'],
                [['number_licences'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'number_licences' => Yii::t('frontend', 'Number of licences'),
        ];
    }

}
