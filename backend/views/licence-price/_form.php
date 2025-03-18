<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LicencePrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="licence-price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'minimal_number_licences')->textInput() ?>

    <?= $form->field($model, 'maximum_number_licences')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
