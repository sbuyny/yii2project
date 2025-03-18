<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * LicenceController implements the CRUD actions for Licence model.
 */
class ConsolidatorController extends Controller
{

    /**
     * Lists all Licence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where("date_consolidator>0 AND user_type!='Consolidator'"),
        ]);
        $dataProvider->sort->defaultOrder = ['date_consolidator' => SORT_ASC];

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Give licence.
     * If Give licence is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEnable($id)
    {
        $user = User::findOne(['id' => $id]);
        if($user->user_type =='Broker'){
            //cancel broker licence from another consolidator, if have it
            Yii::$app->db->createCommand("UPDATE licence SET broker_id=0 WHERE broker_id='".$id."'")->execute();
            Yii::$app->db->createCommand("UPDATE certificates SET broker_id=NULL WHERE broker_id='".$id."'")->execute();
            Yii::$app->db->createCommand("UPDATE packages SET broker_id=NULL WHERE broker_id='".$id."'")->execute();
            Yii::$app->db->createCommand("DELETE FROM orders WHERE author_id='".$id."'")->execute();
            
        }
        $user->user_type ='Consolidator';
        $user->date_consolidator =time();
        $consolidator_price = Yii::$app->db->createCommand("SELECT value FROM config WHERE key='consolidator_price'")->queryScalar();
        //$licences = Yii::$app->db->createCommand("UPDATE licence SET broker_id=0 WHERE owner_id='$id'")->execute();
        $user->money = $user->money-$consolidator_price;
        $user->save();
        
        $licence = \common\models\Licence::findOne(['owner_id' => $id]);
        if(isset($licence)){
        $licence->broker_id=0;
        $licence->save();
        $log = new \common\models\LicenceLog();
        $log->setAttributes($licence->attributes);
        $log->licence_id=$licence->id;
        $log->date_changed=time();
        $log->description=Yii::t('common', 'Get Consolidator Licence');
        $log->save();
        }
        return $this->redirect(['index']);
    }
    
    /**
     * Cancel licence.
     * If Cancel licence is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisable($id)
    {
        $user = User::findOne(['id' => $id]);
        $user->date_consolidator ='';
        $user->save();

        return $this->redirect(['index']);
    }

}
