<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LicencePrice */

$this->title = Yii::t('backend', 'Create').' '.Yii::t('backend', 'Licence Prices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Licence Prices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="licence-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
