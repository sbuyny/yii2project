<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Licence;

/**
 * LicenceSearch represents the model behind the search form about `common\models\Licence`.
 */
class LicenceSearch extends Licence
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['licence_number', 'date_register', 'date_start', 'date_finish', 'broker_id', 'owner_id'], 'safe'],
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
        $query = Licence::find();

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

        if (!empty($params['LicenceSearch']['broker_id'])) {
            $this->broker_id=(new \yii\db\Query())  
            ->select(['id'])
            ->from('user')
            ->where(['username' => $params['LicenceSearch']['broker_id']])
            ->scalar();
            if(!$this->broker_id)$this->broker_id=0;
        }
        
        if (!empty($params['LicenceSearch']['owner_id'])) {
            $this->owner_id=(new \yii\db\Query())  
            ->select(['id'])
            ->from('user')
            ->where(['username' => $params['LicenceSearch']['owner_id']])
            ->scalar();
            if(!$this->owner_id)$this->owner_id=0;
        }
        
        if (!empty($params['LicenceSearch']['procent'])) {
            if($params['LicenceSearch']['procent']=='Moderation'){
                $query->andWhere('date_start=0');
                $query->andWhere("licence_number=''");
            }
            if($params['LicenceSearch']['procent']=='Working'){
                $query->andWhere('date_start>0');
                $query->andWhere('date_finish>'.time().' OR date_finish=0');
            }
            if($params['LicenceSearch']['procent']=='Finished'){
                $query->andWhere('date_start>0');
                $query->andWhere('date_finish<'.time().' AND date_finish!=0');
            }
        }
        
        if (!empty($params['LicenceSearch']['date_start'])) {
            $c1=split('-',$params['LicenceSearch']['date_start']);
            $start=mktime(0, 0, 0, $c1[1], $c1[2], $c1[0]);
            $finish=mktime(0, 0, 0, $c1[1], $c1[2]+1, $c1[0]);
            $query->andWhere(['between', 'date_start', $start, $finish]);
        }
        
        if (!empty($params['LicenceSearch']['date_finish'])) {
            $c1=split('-',$params['LicenceSearch']['date_start']);
            $start=mktime(0, 0, 0, $c1[1], $c1[2], $c1[0]);
            $finish=mktime(0, 0, 0, $c1[1], $c1[2]+1, $c1[0]);
            $query->andWhere(['between', 'date_finish', $start, $finish]);
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'licence_number' => $this->licence_number,
            'broker_id' => $this->broker_id,
            'owner_id' => $this->owner_id,
            //'procent' => $this->procent,
            //'price' => $this->price,
        ]);

        //$query->andFilterWhere(['like', 'licence_number', $this->licence_number]);
        if(!empty($params['LicenceSearch']['broker_id']))$this->broker_id=$params['LicenceSearch']['broker_id'];
        if(!empty($params['LicenceSearch']['owner_id']))$this->owner_id=$params['LicenceSearch']['owner_id'];
        if(!empty($params['LicenceSearch']['procent']))$this->procent=$params['LicenceSearch']['procent'];
        if(!empty($params['LicenceSearch']['date_start']))$this->date_start=$params['LicenceSearch']['date_start'];
        if(!empty($params['LicenceSearch']['date_finish']))$this->date_finish=$params['LicenceSearch']['date_finish'];

        return $dataProvider;
    }
}
