<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\LicenceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="licence-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<div  class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('frontend', 'Search') ?></div>
        <div class="panel-body">


            <div class="input-group input-group-sm ">
                <div class="row">
                    
                    <div class="col-xs-4">
                    <?=
                    $form->field($model, 'date_start')->widget(\dosamigos\datepicker\DatePicker::className(), [
                        'template' => '{addon}{input}',
                        'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                        ]
                    ])
                    ?>
                    </div>
                    <div class="col-xs-5">
                        <?=
                    $form->field($model, 'date_finish')->widget(\dosamigos\datepicker\DatePicker::className(), [
                        'template' => '{addon}{input}',
                        'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                        ]
                    ])
                    ?>
                    </div> 
                   <div class="col-xs-3">
                        <?= $form->field($model, 'licence_number') ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-3">
                        <?= $form->field($model, 'procent')->dropDownList([
                        ''=>'',
                        'Moderation' => Yii::t('common', 'Moderation'),
                        'Working' => Yii::t('common', 'Working'),
                        'Finished' => Yii::t('common', 'Finished'),
                    ])->label(Yii::t('backend', 'Status')) ?>
                    </div>
                    <div class="col-xs-3">
                        <?= $form->field($model, 'broker_id')->label(Yii::t('common', 'Broker')) ?>
                    </div>
                    <div class="col-xs-3">
                        <?= $form->field($model, 'owner_id')->label(Yii::t('frontend', 'Owner')) ?>
                    </div>
                    <div class="col-xs-3" style="margin-top:24px;">
                        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
                        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>

    <?php ActiveForm::end(); ?>

</div>
