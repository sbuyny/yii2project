<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * LicenceForm is the model behind the buy licences form.
 */
class LicenceBrokerForm extends Model {

    public $procent;
    public $broker_id;
    public $licence_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['broker_id','licence_id','procent'], 'integer'],
                [['broker_id','licence_id','procent'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'procent' => Yii::t('frontend', 'Procent'),
            'licence_id' => Yii::t('frontend', 'Licence'),
            'broker_id' => Yii::t('frontend', 'Licence'),
        ];
    }

}
