<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LicenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Licences');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licence-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id',
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
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('frontend', 'Owner'),
                'options' => ['width' => '50'],
                'format'=>'raw',
                'value'=>function ($data) {
                    return Html::a(common\models\User::findOne($data->owner_id)->username, "?r=user%2Fview&id=".$data->owner_id);
                },

            ],
            //'owner_id',
            //'procent',
            //'price',
            //'licence_number',
            // 'documents_file',
            [
                'attribute' => 'date_register',
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
            ],
            [
                'attribute' => 'date_start',
                'value'=>function ($data) {
                    if($data->date_start){
                    return date('Y-m-d',$data->date_start);
                    }
                },
            ],
            [
                'attribute' => 'date_finish',
                'value'=>function ($data) {
                    if($data->date_finish){
                    return date('Y-m-d',$data->date_finish);
                    }
                },
            ],
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
                'template' => '{view} {logs}',
                'buttons' => [
                    'logs' => function ($url,$model) {
                        return Html::a(
                        '<span class="fa fa-clone" title="'.Yii::t('common', 'Licence Logs').'"></span>', 
                        '?r=licence-log%2Findex&LicenceLogSearch%5Blicence_id%5D='.$model->id);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
