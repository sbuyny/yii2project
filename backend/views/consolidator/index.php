<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LicenceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('common', 'Consolidators');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licence-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'date_consolidator',
                'label' => Yii::t('backend', 'Date Consolidator'),
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
            ],
            [
                'attribute' => 'id',
                'label' => Yii::t('backend', 'User'),
                'format'=>'raw',
                'value'=>function ($data) {
                    return Html::a($data->username, "?r=user%2Fview&id=".$data->id);
                },

            ],
            [
                'attribute' => 'fio',
                'label' => Yii::t('backend', 'FIO'),
            ],
            [
                'attribute' => 'passport',
                'label' => Yii::t('common', 'Passport'),
            ],
            [
                'attribute' => 'inn',
                'label' => Yii::t('common', 'INN'),
            ],
            [
                'attribute' => 'user_type',
                'label' => Yii::t('backend', 'Status'),
                'value' => function($model) {
                    if(!$model->user_type)$model->user_type='User';
                    return Yii::t('common',$model->user_type);
                }, 
            ],     
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{enable} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{disable}',
                'buttons' => [
                    'enable' => function ($url,$model) {
                        return Html::a(
                        '<span class="fa fa-check" title="'.Yii::t('common', 'Give licence').'"></span>', 
                        '?r=consolidator%2Fenable&id='.$model->id);
                    },
                    'disable' => function ($url,$model) {
                        return Html::a(
                        '<span class="fa fa-close" title="'.Yii::t('common', 'Cancel licence').'"></span>', 
                        '?r=consolidator%2Fdisable&id='.$model->id);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
