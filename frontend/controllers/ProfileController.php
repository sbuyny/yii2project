<?php

/*
 * @link https://itnavigator.org/
 */

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use frontend\models\ProfileForm;
use common\models\User;
use common\models\Licence;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use common\models\LicenceLog;

/**
 *  Profile controller
 *
 * @author Vyacheslav Bodrov <bigturtle@i.ua>
 * @since 1.0
 */
class ProfileController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                        [
                        'actions' => ['index',
                            'validation-profile-form'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                     [
                        'actions' => ['validation-profile-form'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
        ];
    }

    /**
     * Displays  profile of user
     *
     * @return mixed
     */
    public function actionIndex() {

        $modelProfileForm = new ProfileForm();
        
        $userid=Yii::$app->user->identity->id;

        $user = User::findOne(['id' => $userid]);

        $modelProfileForm->setAttributes(Yii::$app->user->identity->attributes);
        
        $licence = Licence::findOne(['broker_id' => $userid]);
        $num_deals = Yii::$app->db->createCommand("SELECT COUNT(*) FROM log_deals WHERE seller_id='$userid' OR  buyer_id='$userid'")->queryScalar();
        $licence_price = Yii::$app->db->createCommand("SELECT value FROM config WHERE key='licence_price'")->queryScalar();
        $consolidator_price = Yii::$app->db->createCommand("SELECT value FROM config WHERE key='consolidator_price'")->queryScalar();
        $zayavka = 0;
        $earnings = 0;
        if(isset($modelProfileForm->date_consolidator))$modelProfileForm->date_consolidator=date('Y/m/d',$modelProfileForm->date_consolidator);
            
        if($licence){
            if($licence->date_register>0)$licence->date_register = date('d/m/Y',$licence->date_register);
            if($licence->date_start>0)$licence->date_start = date('d/m/Y',$licence->date_start);
            if($licence->date_finish>0)$licence->date_finish = date('d/m/Y',$licence->date_finish);
            $earnings = 0;
            $zayavka = 1;
        }

        if (isset($_POST['save_profile'])) {
            $return=ProfileController::saveUserProfile($userid);
            if($return)Yii::$app->session->setFlash('success', Yii::t('common', 'Your profile has been saved'));
            return $this->refresh();
        } 
        elseif (isset($_POST['add_licence_request'])) {
            //send licence request
           $return=ProfileController::addLicenceRequest($userid);
           if($return)Yii::$app->session->setFlash('success', Yii::t('common', 'License request has been sent'));
           return $this->refresh();
        }
        elseif (isset($_POST['add_consolidator_request'])) {
            //send add_consolidator_request
            $return=ProfileController::sendConsolidatorRequest($userid);
            if($return)Yii::$app->session->setFlash('success', Yii::t('common', 'License request has been sent'));
            return $this->refresh();
        }
        else {
            //show forms
            return $this->render('index', ['model' => $modelProfileForm, 'licence' => $licence, 'num_deals' => $num_deals, 'earnings' => $earnings, 'zayavka' => $zayavka, 'licence_price' => $licence_price, 'consolidator_price' => $consolidator_price]);
        }
    }

    /**
     * Validation profile form
     *
     * 
     * @return mixed
     */
    public function actionValidationProfileForm() {

        $model = new ProfileForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
    
    //send add_consolidator_request
    public function sendConsolidatorRequest($userid) {
        $modelProfileForm = new ProfileForm();
        $modelProfileForm->load(Yii::$app->request->post());
        if($modelProfileForm){
            $user = User::findOne(['id' => $userid]);
            $user->fio = $modelProfileForm->fio;
            $user->passport = $modelProfileForm->passport;
            $user->inn = $modelProfileForm->inn;
            $user->date_consolidator = time();
            if ($user->save()) {
                return true;
            }
        }
    }
    
    //add_licence_request
    public function addLicenceRequest($userid) {
        $modelProfileForm = new ProfileForm();
        $modelProfileForm->load(Yii::$app->request->post());
        if($modelProfileForm){
            $user = User::findOne(['id' => $userid]);
            $user->fio = $modelProfileForm->fio;
            $user->passport = $modelProfileForm->passport;
            $user->inn = $modelProfileForm->inn;
            if ($user->save()) {
                $licence = new Licence();
                $licence->broker_id = $userid;
                $licence->owner_id = $userid;
                $licence->procent = 0;
                $licence->price = 0;
                $licence->licence_number = '';
                $licence->date_register = time();
                $licence->date_start = 0;
                $licence->date_finish = 0;
                
                $file = \yii\web\UploadedFile::getInstance($modelProfileForm, 'documents_file');
                $filename=$userid.'_'.$file->name;
                $file->saveAs('upload/'.$filename);
                chmod('upload/'.$filename, 0777);
                
                $licence->documents_file = $filename;
                $licence->save();
                
                $log = new LicenceLog();
                $log->setAttributes($licence->attributes);
                $log->licence_id=$licence->id;
                $log->date_changed=time();
                $log->description=Yii::t('common', 'License request has been sent');
                $log->save();

                return true;
            }
        }
    }
    
    //save user profile
    public function saveUserProfile($userid) {
        $modelProfileForm = new ProfileForm();
        $modelProfileForm->load(Yii::$app->request->post()); 
        if($modelProfileForm->validate()){
            $user = User::findOne(['id' => $userid]);
            $user->username = $modelProfileForm->username;
            $user->email = $modelProfileForm->email;
            $user->fio = $modelProfileForm->fio;
            $user->tel = $modelProfileForm->tel;
            $user->is_individual = $modelProfileForm->is_individual;
            $user->contact = $modelProfileForm->contact;
            $user->firm_name = $modelProfileForm->firm_name;

            if ($user->save()) {
                return true;
            }
        }
    }
}
