<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\LicencePrice */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Licence Price',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Licence Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="licence-price-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
