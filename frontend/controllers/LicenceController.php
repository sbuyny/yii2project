<?php

/*
 * @link https://itnavigator.org/
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\InvalidParamException;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use common\models\Licence;
use frontend\models\LicenceForm;
use frontend\models\LicenceBrokerForm;
use frontend\models\LicenceUserSearch;
use common\models\LicenceLog;
use common\models\User;
use common\models\LicencePrice;

/**
 * The base of the licences management controller
 * 
 * @author Sergey Booyny
 * @since 1.0
 */
class LicenceController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['index'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * Display the list licences of current consolidator
     *
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        $query = Licence::find();
        $model = new LicenceForm();
        
        $licence_broker = new LicenceBrokerForm();
        $user_search = new LicenceUserSearch();
        $licence_prices= new ActiveDataProvider([
            'query' => LicencePrice::find(),
        ]);
        $query_user = User::find();
        
        //search users
        if ( isset(Yii::$app->request->post()['search_users']) && $user_search->load(Yii::$app->request->post()) && $user_search->validate()) {
           if($user_search->username){ 
               $username=$user_search->username;
               $userid=(new \yii\db\Query())  
                ->select(['id'])
                ->from('user')
                ->where(['username' => $username])
                ->scalar();
             if(!is_integer($userid))$userid=0;
             $query_user->andFilterWhere(['id' => $userid]);
             $user_search->username=$username;
           }
           if($user_search->fio) $query_user->andFilterWhere(['like', 'fio', $user_search->fio]);
        }
        
        //buy some licences
        if ( isset(Yii::$app->request->post()['add_consolidator_request'])){
            $result=LicenceController::buyLicences();
            if($result)Yii::$app->session->setFlash('success', Yii::t('frontend', 'Licences buyed by consolidator'));
            else Yii::$app->session->setFlash('danger', Yii::t('frontend', 'You have not enough money on your account'));
            return $this->refresh();
        }
        //end buy licences
        
        //remove licence from broker
        if (isset(Yii::$app->request->get()['stop']))LicenceController::removeLicence();
        //end remove licence

        //set licence to broker
        if (isset(Yii::$app->request->post()['add_broker_licence']))LicenceController::setLicence();
        //set licence to broker
        
        
        $query->andFilterWhere(['owner_id' => Yii::$app->user->identity->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        
        
        $query_user->andFilterWhere(['user_type' => 'User']);
        $users = new ActiveDataProvider([
            'query' => $query_user,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC],
            ],
        ]);
        
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'licence_broker' => $licence_broker,
                    'licence_prices' => $licence_prices,
                    'users' => $users,
                    'user_search' => $user_search,
        ]);
    }

    //remove licence from broker
    public function removeLicence() {
            $licence = Licence::findOne(['id' => Yii::$app->request->get()['stop'], 'owner_id' => Yii::$app->user->identity->id ]);
            if($licence){
            
            $user = User::findOne(['id' => $licence->broker_id]);
            $user->user_type = 'User';
            $user->save();
            
            Yii::$app->db->createCommand("UPDATE certificates SET broker_id=NULL WHERE broker_id='".$licence->broker_id."'")->execute();
            Yii::$app->db->createCommand("UPDATE packages SET broker_id=NULL WHERE broker_id='".$licence->broker_id."'")->execute();
            Yii::$app->db->createCommand("DELETE FROM orders WHERE author_id='".$licence->broker_id."'")->execute();
            
            $log = new LicenceLog();
            $log->setAttributes($licence->attributes);
            $log->licence_id=$licence->id;
            $log->date_changed=time();
            $log->description=Yii::t('common', 'Stop licence');
            $log->save();
            
            $licence->broker_id = 0;
            $licence->save();
            
            Yii::$app->session->setFlash('success', Yii::t('common', 'Stop licence ready'));
            }
            return $this->redirect(['index']);
    }
    
    
    //set licence to broker
    public function setLicence() {
        $form_licence_broker=new LicenceBrokerForm();
        if($form_licence_broker->load(Yii::$app->request->post()) && $form_licence_broker->validate()){
            $licence = Licence::findOne(['id' => $form_licence_broker->licence_id, 'owner_id' => Yii::$app->user->identity->id ]);
            if($licence){
            
            $user = User::findOne(['id' => $form_licence_broker->broker_id]);
            $user->user_type = 'Broker';
            $user->save();
            
            $licence->procent = $form_licence_broker->procent;
            $licence->broker_id = $form_licence_broker->broker_id;
            $licence->save();
            
            $log = new LicenceLog();
            $log->setAttributes($licence->attributes);
            $log->licence_id=$licence->id;
            $log->date_changed=time();
            $log->description=Yii::t('common', 'Give licence to broker');
            $log->save();
            
            Yii::$app->session->setFlash('success', Yii::t('common', 'Give licence to broker ready'));
            }
            return $this->redirect(['index']);
        }
    }
    
    //buy some licences
    public function buyLicences() {
        $model = new LicenceForm();
            if($model->load(Yii::$app->request->post()) && $model->validate()){
            $number=$model->number_licences;
            $licences_price = Yii::$app->db->createCommand("SELECT price FROM licence_price WHERE minimal_number_licences<='$number' AND maximum_number_licences>='$number'")->queryScalar();
            $userid=Yii::$app->user->identity->id;
            $user = User::findOne(['id' => $userid]);
            
            if(($licences_price*$number) > $user->money){
                return false; 
            }
            else{
                
            for($i=0;$i<$number;$i++){
                $licence = new Licence();
                $licence->broker_id = 0;
                $licence->owner_id = $userid;
                $licence->procent = 0;
                $licence->price = $licences_price;
                $licence->licence_number = md5(rand ( 0 , 99999999999999));
                $licence->date_register = time();
                $licence->date_start = time();
                $licence->date_finish = 0;
                $licence->documents_file = '';
                $licence->save();
                
                $log = new LicenceLog();
                $log->setAttributes($licence->attributes);
                $log->licence_id=$licence->id;
                $log->date_changed=time();
                $log->description=Yii::t('frontend', 'Licences buyed by consolidator');
                $log->save(); 
            }
                $user->money = $user->money - $licences_price * $number;
                $user->save();
                
                return true; 
            }
        }
    }

}
