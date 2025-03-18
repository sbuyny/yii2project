<?php

namespace backend\controllers;

use Yii;
use common\models\Licence;
use backend\models\LicenceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\LicenceLog;

/**
 * LicenceController implements the CRUD actions for Licence model.
 */
class LicenceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Licence models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LicenceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Licence model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * Deletes an existing Licence model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Give licence.
     * If Give licence is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEnable($id)
    {
        $model = $this->findModel($id);
        $model->date_start=time();
        $model->date_finish=0;
        $model->licence_number=md5(rand ( 0 , 99999999999999));
        
        $user = User::findOne(['id' => $model->owner_id]);
        $licence_price = Yii::$app->db->createCommand("SELECT value FROM config WHERE key='licence_price'")->queryScalar();
        $user->money = $user->money - $licence_price;
        
        $model->price=$licence_price;
        
        $user->user_type ='Broker';
        $user->save();
        $model->save();
        
        $log = new LicenceLog();
        $log->setAttributes($model->attributes);
        $log->licence_id=$model->id;
        $log->date_changed=time();
        $log->description=Yii::t('common', 'Give licence');
        $log->save();

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
        $model = $this->findModel($id);
        $model->date_finish=time();
        $model->save();
        
        $log = new LicenceLog();
        $log->setAttributes($model->attributes);
        $log->licence_id=$model->id;
        $log->date_changed=time();
        if(!$model->licence_number)$log->description=Yii::t('common', 'Cancel licence');
        else $log->description=Yii::t('common', 'Stop licence');
        $log->save();
        
        $user = User::findOne(['id' => $model->owner_id]);
        $user->user_type ='User';
        $user->save();

        return $this->redirect(['index']);
    }
    
    /**
     * Return licence.
     * If Return licence is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionReturn($id)
    {
        $model = $this->findModel($id);
        $model->date_finish=0;
        $model->save();
        
        $log = new LicenceLog();
        $log->setAttributes($model->attributes);
        $log->licence_id=$model->id;
        $log->date_changed=time();
        $log->description=Yii::t('common', 'Return licence');
        $log->save();
        
        $user = User::findOne(['id' => $model->owner_id]);
        $user->user_type ='Broker';
        $user->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Licence model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Licence the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Licence::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
