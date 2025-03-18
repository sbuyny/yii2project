<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if($model->user_type=='Broker'){ ?>
        <?= Html::a(Yii::t('common', 'Licences'), '?r=licence%2Findex&LicenceSearch%5Bbroker_id%5D='.$model->username, ['class' => 'btn btn-primary']) ?>
        <?php } ?>
        <?php if($model->user_type=='Consolidator'){ ?>
        <?= Html::a(Yii::t('common', 'Licences'), '?r=licence%2Findex&LicenceSearch%5Bowner_id%5D='.$model->username, ['class' => 'btn btn-primary']) ?>
        <?php } ?>
        <?=
        Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            'fio',
            'passport',
            'inn',
            [
                'attribute' => 'inn',
                'label' => Yii::t('common', 'Documents File'),
                'format'=>'raw',
                'value'  => call_user_func(function ($data) {
                 $file=Yii::$app->db->createCommand("SELECT documents_file FROM licence WHERE owner_id='".$data->id."' AND documents_file!=''")->queryScalar();
                return Html::a($file,  Yii::$app->getUrlManager()->getBaseUrl()."/upload/".$file);
                }, $model),
            ],
            'tel',
            'is_individual:boolean',
            'contact',
            'firm_name',
            'money',
            'status:boolean',
            [
                'attribute' => 'user_type',
                'label' => Yii::t('backend', 'Status'),
                'value'  => call_user_func(function ($data) {
                if(!$data->user_type)$data->user_type='User';
                return Yii::t('common', $data->user_type);
                }, $model),
            ],
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
                'label' => Yii::t('common', 'Signup'),
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'Y-MM-dd HH:mm'],
                'label' => Yii::t('backend', 'Changed'),
            ],
        ],
    ])
    ?>

</div>
