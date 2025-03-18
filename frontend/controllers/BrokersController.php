<?php

/*
 * @link https://itnavigator.org/
 */

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\OrderBuyForm;
use yii\bootstrap\ActiveForm;
use common\models\Packages;
use yii\data\Pagination;
use frontend\models\BrokersForm;
use common\models\User;
use yii\data\ActiveDataProvider;
use common\models\Broker;
use common\models\Certificate;
use common\models\Order;
use common\models\Offer;
use frontend\models\PackagesUpdate;

/**
 *  Brokers controller
 *
 * @author Vyacheslav Bodrov <bigturtle@i.ua>
 * @since 1.0
 */
class BrokersController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => [
                            'index',
                            'validation-packages-form',
                            'create-packages',
                            'view',
                            'update',
                            'delete',
                            'broker-packages',
                            'broker-certificates'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'profile' => ['post'],
                    'certificates' => ['post'],
                    'orders' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Display packages
     * 
     * @return mixed
     */
    public function actionIndex() {

        $model = new BrokersForm();
        $brokers = User::find()->where(['user_type' => User::TYPE_BROKER]);
        $dataProvider = new ActiveDataProvider([
            'query' => $brokers,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ], 'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $model->load(Yii::$app->request->post());

        $brokersPost = Yii::$app->request->post();
        $brokersArray['brokers_id'] = array();


        if (!empty(Yii::$app->request->post())) {

            if (isset($brokersPost['broker_id'])) {
                foreach ($brokersPost['broker_id'] as $selection) {

                    array_push($brokersArray['brokers_id'], (int) $selection);
                }
            }
            $model->brokers_id = $brokersArray['brokers_id'];
            $model->procents = $brokersPost['procent'];

            //  Packages::updateAll(['broker_id' => NULL], ['broker_id' => $model->brokers_id, 'user_id' => Yii::$app->user->identity->id]);
            // Certificate::updateAll(['broker_id' => NULL], ['broker_id' => $model->brokers_id, 'user_id' => Yii::$app->user->identity->id]);
            // $packagesNull = Packages::findOne()->where();

            $certificatesNull = Certificate::find()
                    ->where(['<>', 'broker_id', $model->brokers_id])
                    ->where(['user_id' => Yii::$app->user->identity->id])
                    ->where(['<>', 'broker_id', null])
                    ->all();

            foreach ($certificatesNull as $certificateNull) {

                $certificateNull->broker_id = null;
                $certificateNull->update(false);
            }


            $packagesNull = Packages::find()
                    ->where(['<>', 'broker_id', $model->brokers_id])
                    ->where(['user_id' => Yii::$app->user->identity->id])
                    ->where(['<>', 'broker_id', null])
                    ->all();

            foreach ($packagesNull as $packageNull) {
                $packageNull->broker_id = null;
                $packageNull->author_id = Yii::$app->user->identity->id;
                $packageNull->update(false);
            }


//            $ordersInactive = Order::find()
//                    ->where(['<>', 'broker_id', $model->brokers_id])
//                    ->where(['user_id' => Yii::$app->user->identity->id])
//                    ->where(['<>', 'broker_id', null])
//                    ->all();



            if ($model->save()) {

                $brokersInactive = Broker::find()
                                ->where(['is_active' => Broker::STATUS_INACTIVE])
                                ->where(['user_id' => Yii::$app->user->identity->id])->all();

                foreach ($brokersInactive as $brokerInactive) {


                    $ordersInactive = Order::find()
                            ->where(['=', 'author_id', $brokerInactive->broker_id])
                            ->where(['user_id' => Yii::$app->user->identity->id])
                            ->all();


                    foreach ($ordersInactive as $orderInactive) {
                        $orderInactive->type = Order::TYPE_CLOSE;


                        Offer::updateAll(['status' => Offer::STATUS_REJECT], 'user_id = ' . Yii::$app->user->identity->id . ' and  source_id = ' . $orderInactive->id);
                        $orderInactive->update(false);
                    }

                    Packages::updateAll(['broker_id' => NULL], ['broker_id' => $brokerInactive->broker_id, 'user_id' => Yii::$app->user->identity->id]);
                    Certificate::updateAll(['broker_id' => NULL], ['broker_id' => $brokerInactive->broker_id, 'user_id' => Yii::$app->user->identity->id]);
                }


                Yii::$app->session->setFlash('success', 'Брокер выбран.');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка брокер не выбран.');
                return $this->refresh();
            }
        } else {
            return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider]);
        }
    }

    /**
     * Validation Packages form packages
     *
     * 
     * @return mixed
     */
    public function actionValidationBrokersForm() {
        $model = new PackagesForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Updates an existing Broker Certificate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBrokerCertificates($id) {
        $model = new Broker();
        $broker = $model->findOne($id);

        $certificates = Certificate::find()->where(['certificates.user_id' => Yii::$app->user->identity->id/*, 'certificates.package_id' => null*/]);

        $dataCertificate = new ActiveDataProvider([
            'query' => $certificates,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ], 'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        $certificatesPost = Yii::$app->request->post();
        $certificatesArray['certificates'] = array();

        if (!empty(Yii::$app->request->post())) {

            $certificate = Yii::$app->db->createCommand('SELECT package_id, certificate_code FROM certificates WHERE user_id='.Yii::$app->user->identity->id.' AND broker_id='.$id)->queryAll();
                   
          foreach ($certificate as $v) {
                Yii::$app->db->createCommand('UPDATE certificates SET broker_id=NULL WHERE certificate_code=\''.$v['certificate_code'].'\'')->execute();
                if($v['package_id'])$package_owner = Yii::$app->db->createCommand('SELECT author_id FROM packages WHERE id=\''.$v['package_id'].'\'')->queryScalar();
                $v['certificate_code']=trim($v['certificate_code']);
                $noremove=0;
                foreach ($certificatesPost['certificates'] as $selection) {
                    if($selection==$v['certificate_code'])$noremove=1;
                }
                if(isset($package_owner) && $package_owner != Yii::$app->user->identity->id && $noremove==0){
                    //update package parameters if remove some certificates from it
                    Yii::$app->db->createCommand('UPDATE certificates SET package_id=NULL WHERE certificate_code=\''.$v['certificate_code'].'\'')->execute();
                    $certificates_update = Yii::$app->db->createCommand('SELECT certificate_code FROM certificates WHERE package_id='.$v['package_id'])->queryAll();
                    $packages_update = new PackagesUpdate();
                    $packages_update->certificates = $certificates_update;
                    $packages_update->package = $v['package_id'];
                    $packages_update->save();
                }
            }
            //Certificate::updateAll(['broker_id' => NULL], 'user_id = ' . Yii::$app->user->identity->id . ' and  broker_id = ' . $id);
            if (isset($certificatesPost['certificates'])) {
                foreach ($certificatesPost['certificates'] as $selection) {
                    array_push($certificatesArray['certificates'], $selection);
                    $brokerCertificates = Certificate::find()->where(['certificates.certificate_code' => $selection])->one();
                    $brokerCertificates->broker_id = $id;
                    $brokerCertificates->save();
                }

                Yii::$app->session->setFlash('success', 'Права на сертификат отданы.');
            } return $this->refresh();
        } else {

            return $this->render('brokerCertificates', ['model' => $model,
                        'dataCertificate' => $dataCertificate
            ]);
        }
    }

    /**
     * Updates an existing Broker Package model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBrokerPackages($id) {
        $model = new Broker();
        $broker = $model->findOne($id);
        $packages = Packages::find()->where(['packages.user_id' => Yii::$app->user->identity->id]);
        if (Yii::$app->user->identity->user_type == User::TYPE_BROKER) {
            $clients_id = Broker::find()->select(['user_id'])
                            ->where(['broker_id' => Yii::$app->user->identity->id])->column();
            $packages = Packages::find()->where(['packages.user_id' => $clients_id]);
        }
        $dataPackages = new ActiveDataProvider([
            'query' => $packages,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ], 'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        $packagesPost = Yii::$app->request->post();
        $packagesArray['packages'] = array();
        if (!empty(Yii::$app->request->post())) {
            $packages = Yii::$app->db->createCommand('SELECT id FROM packages WHERE user_id='.Yii::$app->user->identity->id.' AND broker_id='.$id)->queryAll();     
            foreach ($packages as $v) {
                $noremove=0;
                if (isset($packagesPost['packages'])){
                foreach ($packagesPost['packages'] as $selection) {
                    if($selection==$v['id'])$noremove=1;
                }
                }
                if($noremove==0){
                    //update certificates brokers if remove some packages from broker
                    Certificate::updateAll(['broker_id' => NULL], 'package_id = ' . $v['id']);
                    //remove orders and offers if package remove from broker
                    Yii::$app->db->createCommand('DELETE FROM orders WHERE source_id='.$v['id'])->execute();
                    Yii::$app->db->createCommand('DELETE FROM offers WHERE source_id='.$v['id'])->execute();
                }
            }
            Packages::updateAll(['broker_id' => NULL], 'user_id = ' . Yii::$app->user->identity->id . ' and  broker_id = ' . $id);
            if (isset($packagesPost['packages'])) {
                foreach ($packagesPost['packages'] as $selection) {
                    array_push($packagesArray['packages'], $selection);
                    $brokerPackages = Packages::find()->where(['packages.id' => $selection])->one();
                    $brokerPackages->broker_id = $id;
                    Certificate::updateAll(['broker_id' => $id], 'user_id = ' . Yii::$app->user->identity->id . ' and  package_id = ' . $brokerPackages->id);
                    $brokerPackages->save();
                }
                Yii::$app->session->setFlash('success', 'Права на пакеты отданы.');
            } return $this->refresh();
        } else {
            return $this->render('brokerPackages', [
                        'model' => $model, 'dataPackages' => $dataPackages
            ]);
        }
    }

}
