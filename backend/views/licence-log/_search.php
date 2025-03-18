<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LicenceLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="licence-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

<div class="row">
    <div class="col-xs-4">
    <?= $form->field($model, 'broker_id') ?>
    <?= $form->field($model, 'licence_id')->hiddenInput()->label(false) ?>
    </div>
    <div class="col-xs-4" style="margin-top:24px;">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
