<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * LicenceForm is the model behind the buy licences form.
 */
class LicenceUserSearch extends Model {

    public $username;
    public $fio;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['username','fio'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => Yii::t('frontend', 'Username'),
            'fio' => Yii::t('backend', 'FIO'),
        ];
    }

}
