<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LicenceLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Licence Logs');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common','Licences'), 'url' => ['licence/index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="licence-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= DetailView::widget([
        'model' => $licence,
        'attributes' => [
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
                }, $licence),
            ],
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('frontend', 'Owner'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                return Html::a(common\models\User::findOne($data->owner_id)->username, "?r=user%2Fview&id=".$data->owner_id);
                }, $licence),
            ],
            'licence_number',
            'price',
            'procent',
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
                }, $licence),
            ],
        ],
    ]) ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'date_changed',
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
            ],
            'description',
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('frontend', 'Broker'),
                'options' => ['width' => '50'],
                'format'=>'raw',
                'value'=>function ($data) {
                    $broker=$data->broker_id;
                    if(isset($broker) && $broker>0){
                        $username=common\models\User::findOne($broker)->username;
                        return Html::a($username, "?r=user%2Fview&id=".$broker);
                    }
                },

            ],
            'procent',
            [
                'attribute' => 'date_start',
                'options' => ['width' => '50'],
                'format'=>'raw',
                'label' => Yii::t('backend', 'Status'),
                'value'=>function ($data) {
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
                },
            ],           
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
