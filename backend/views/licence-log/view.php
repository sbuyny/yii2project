<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LicenceLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('common','Licences'), 'url' => ['licence/index', 'licence_id' => 1]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Licence Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licence-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

   

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'date_changed',
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
            ],
            'description',
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('frontend', 'Broker'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                $broker=$data->broker_id;
                    if(isset($broker) && $broker>0){
                        $username=common\models\User::findOne($broker)->username;
                        return Html::a($username, "?r=user%2Fview&id=".$broker);
                    }
                }, $model),
            ],
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('frontend', 'Owner'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                return Html::a(common\models\User::findOne($data->owner_id)->username, "?r=user%2Fview&id=".$data->owner_id);
                }, $model),
            ],
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('backend', 'FIO'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                 return common\models\User::findOne($data->owner_id)->fio;
                }, $model),
            ],
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('common', 'Passport'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                 return common\models\User::findOne($data->owner_id)->passport;
                }, $model),
            ],
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('common', 'INN'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                 return common\models\User::findOne($data->owner_id)->inn;
                }, $model),
            ],
            [
                'attribute' => 'documents_file',
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                return Html::a($data->documents_file,  Yii::$app->getUrlManager()->getBaseUrl()."/upload/".$data->documents_file);
                }, $model),
            ],
            'licence_number',
            'price',
            'procent',
            [
                'attribute' => 'date_register',
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
            ],
            [
                'attribute' => 'date_start',
                'value'=>call_user_func(function ($data) {
                    if($data->date_start){
                    return date('Y-m-d',$data->date_start);
                    }
                }, $model)
            ],
            [
                'attribute' => 'date_finish',
                'value'=>call_user_func(function ($data) {
                    if($data->date_finish){
                    return date('Y-m-d',$data->date_finish);
                    }
                }, $model)
            ],
            [
                'attribute' => 'documents_file',
                'label' => Yii::t('backend', 'Status'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                    if(!$data->date_start && !$data->date_finish && !$data->licence_number){
                    return Html::tag('span', Yii::t('common', 'Moderation'), ['style' => ['color' => '#f0ad4e']]);
                    }
                    if(!$data->date_start && $data->date_finish && !$data->licence_number){
                    return Html::tag('span', Yii::t('common', 'Cancelled'), ['style' => ['color' => '#d9534f']]);
                    }
                    if($data->date_start>0 && ($data->date_finish==0 || $data->date_finish > time())){
                    return Html::tag('span', Yii::t('common', 'Working'), ['style' => ['color' => '#5cb85c']]);
                    }
                    if($data->date_finish<time() && $data->licence_number){
                    return Html::tag('span', Yii::t('common', 'Finished'), ['style' => ['color' => '#d9534f']]);
                    }
                }, $model),
            ],
        ],
    ]) ?>

</div>
