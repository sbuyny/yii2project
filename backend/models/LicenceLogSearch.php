<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LicenceLog;

/**
 * LicenceLogSearch represents the model behind the search form about `common\models\LicenceLog`.
 */
class LicenceLogSearch extends LicenceLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['broker_id','licence_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LicenceLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($params['LicenceLogSearch']['broker_id'])) {
            $this->broker_id=(new \yii\db\Query())  
            ->select(['id'])
            ->from('user')
            ->where(['username' => $params['LicenceLogSearch']['broker_id']])
            ->scalar();
            if(!$this->broker_id)$this->broker_id=0;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'broker_id' => $this->broker_id,
            'licence_id' => $this->licence_id,
        ]);
        
        if(!empty($params['LicenceLogSearch']['broker_id']))$this->broker_id=$params['LicenceLogSearch']['broker_id'];
        

        return $dataProvider;
    }
}
