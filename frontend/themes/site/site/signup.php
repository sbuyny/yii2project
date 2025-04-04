<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
    function isFirm(date){
        if(date.checked === true){
            $('.is_firm').show();
        }else{
            $('.is_firm').hide();
        }
    };
JS;

$this->registerCss(".is_firm {display:none;}");
$this->registerJs($script, yii\web\View::POS_BEGIN);
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'email') ?>

            <div class="form-group">
<?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
