<?php

namespace backend\controllers;

use Yii;
use common\models\LicenceLog;
use backend\models\LicenceLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LicenceLogController implements the CRUD actions for LicenceLog model.
 */
class LicenceLogController extends Controller
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
     * Lists all LicenceLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LicenceLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $licence='';
        if(isset($_GET["LicenceLogSearch"]["licence_id"]))$licence = \common\models\Licence::findOne(['id' => $_GET["LicenceLogSearch"]["licence_id"]]);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'licence' => $licence,
        ]);
    }

    /**
     * Displays a single LicenceLog model.
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
     * Finds the LicenceLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LicenceLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LicenceLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
