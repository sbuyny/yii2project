<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Season */

$this->title = Yii::t('backend', 'Create Season');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Seasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="season-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
