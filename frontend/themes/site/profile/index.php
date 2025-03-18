<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
?>
<div class="col-xs-12">
    <p>
    <h2> <?= Yii::t('common', 'Profile') ?></h2>
</p>
</div>

<?php echo $this->render('/dashboard/dashboardTabs'); ?>
<div class="profileform-wrapper">

<div class="row">
    <div class="col-xs-6"> 
    <?php
    $form = ActiveForm::begin([
                'id' => $model->formName(),
                'enableAjaxValidation' => true,
                'validationUrl' => Url::toRoute('validation-profile-form'),
 
    ]);
    ?>

    <input type="hidden" name="save_profile" value="1">
    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'email') ?>

    <?php if($model->fio) echo $form->field($model, 'fio')->textInput(['disabled' => true]) ?>
        
    <?php if($model->passport) echo $form->field($model, 'passport')->textInput(['disabled' => true]) ?>
        
    <?php if($model->inn) echo $form->field($model, 'inn')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'tel')->textInput() ?>

    <?= $form->field($model, 'is_individual')->checkBox(['class' => 'isFirm', 'onchange' => 'isFirm(this);']) ?>
    <div class="is_firm">
        <?= $form->field($model, 'contact')->textInput() ?>

        <?= $form->field($model, 'firm_name')->textInput() ?>                
    </div>
    <div class="form-group">


        <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
    <div class="col-xs-6">
        
        <?php if($model->user_type!='Consolidator' && $zayavka && $licence->licence_number){ ?>
        <br>
        <?php if($model->user_type=='Broker'){?>
        <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseAddPackagesSell" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Broker'); ?>
        </button>
        <?php }?>
        <?php if($model->user_type=='User'){?>
        <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#collapseAddPackagesSell" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Stop licence ready'); ?>
        </button>
        <?php }?>
        <br><br>
        <b><?= Yii::t('common', 'Licence Number') ?>:</b> <?= $licence->licence_number; ?><br><br>
        <b><?= Yii::t('common', 'Date Register') ?>:</b> <?= $licence->date_register; ?><br><br>
        <b><?= Yii::t('common', 'Date Start') ?>:</b> <?= $licence->date_start; ?><br><br>
        <?php if($licence->date_finish){ ?><b><?= Yii::t('common', 'Date Finish') ?>:</b> <?= $licence->date_finish; ?><br><br><?php } ?>
        <?php if($licence->price){ ?><b><?= Yii::t('common', 'Licence Price') ?>:</b> <?= $licence->price; ?><br><br><?php } ?>
        <?php if($licence->procent){ ?><b><?= Yii::t('common', 'Licence Procent') ?>:</b> <?= $licence->procent; ?> %<br><br><?php } ?>
        <b><?= Yii::t('common', 'Number of Deals') ?>:</b> <?= $num_deals; ?><br><br>
        <b><?= Yii::t('common', 'Earnings') ?>:</b> <?= $earnings; ?> руб.<br><br>
        <?php } ?>
        <?php if($model->user_type!='Consolidator' && !$zayavka){ ?>
        <?php 
         Modal::begin([
            'header'=>'<h4>'.Yii::t('common', 'Get Broker Licence').'</h4>',
            'id'=>'modal_window',
         ]);

        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        ?>
        <input type="hidden" name="add_licence_request" value="1">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('frontend', 'Parameter buy') ?></div>
            <div class="panel-body">

                <div class="input-group input-group-sm row">
                    <div class="col-xs-12">       
                        <div class="row">
                            <div class="col-xs-12">     
                                <?= $form->field($model, 'fio')->textInput(); ?>
                            </div> 
                            <div class="col-xs-12">  
                                <br>
                                <?= $form->field($model, 'passport',['options'=>['class'=>'required']])->textInput(); ?>
                            </div> 
                            <div class="col-xs-12">    
                                <br>
                                <?= $form->field($model, 'inn')->textInput(); ?>
                            </div>
                            <div class="col-xs-12">    
                                <br>
                                <?= $form->field($model, 'documents_file')->fileInput() ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-info', 'name' => 'signup-button']) ?>
        </div>
        <script>$('#add_licence').yiiActiveForm('validate', true);</script>
        <?php 
        ActiveForm::end();
        Modal::end();
        ?>
        <button class="btn btn-<?php if($num_deals>0){ ?>primary<?php }else{ ?>disabled<?php } ?>" <?php if($num_deals>0 && $licence_price <= $model->money){ ?> data-toggle="modal" data-target="#modal_window"<?php }elseif($num_deals>0 && $licence_price > $model->money){ ?>onclick="javascript:alert('<?= Yii::t('common', 'You need up your inner balance to: ').$licence_price; ?>');"<?php }else{ ?>onclick="javascript:alert('<?= Yii::t('common', 'You need to make at least 1 deal'); ?>');"<?php } ?> type="button" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Get Broker Licence'); ?>
        </button>
        <br><br>
        <?php }
        if($zayavka && !$licence->licence_number && !$licence->date_finish){?>
        <button class="btn btn-warning" type="button" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Your licence request on moderation'); ?>
        </button>
        <br><br>
        <?php }
        if($zayavka && !$licence->licence_number && $licence->date_finish){?>
        <button class="btn btn-danger" type="button" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Your licence request is cancelled'); ?>
        </button>
        <br><br>
        <?php }
        if(!$model->date_consolidator && $model->user_type!='Consolidator'){?>
        <?php 
         Modal::begin([
            'header'=>'<h4>'.Yii::t('common', 'Get Consolidator Licence').'</h4>',
            'id'=>'modal_window2',
         ]);

        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
        ?>
        <input type="hidden" name="add_consolidator_request" value="1">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('frontend', 'Parameter buy') ?></div>
            <div class="panel-body">

                <div class="input-group input-group-sm row">
                    <div class="col-xs-12">       
                        <div class="row">
                            <div class="col-xs-12">     
                                <?= $form->field($model, 'fio')->textInput(); ?>
                            </div> 
                            <div class="col-xs-12">  
                                <br>
                                <?= $form->field($model, 'passport',['options'=>['class'=>'required']])->textInput(); ?>
                            </div> 
                            <div class="col-xs-12">    
                                <br>
                                <?= $form->field($model, 'inn')->textInput(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('common', 'Save'), ['class' => 'btn btn-info', 'name' => 'signup-button']) ?>
        </div>
        <script>$('#add_consolidator').yiiActiveForm('validate', true);</script>
        <?php 
        ActiveForm::end();
        Modal::end();
        ?>
        <button class="btn btn-primary" 
            <?php if($consolidator_price <= $model->money){ ?>
                data-toggle="modal" data-target="#modal_window2"
                <?php }
                else{ ?>
                onclick="javascript:alert('<?= Yii::t('common', 'You need up your inner balance to: ').$consolidator_price; ?>');"
                <?php }?> 
                type="button" data-toggle="collapse" data-target="#collapseAddPackagesSell" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Get Consolidator Licence'); ?>
        </button>
        <?php }
        if($model->date_consolidator && $model->user_type!='Consolidator'){?>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseAddPackagesSell" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Your licence consolidator request on moderation'); ?>
        </button>
        <?php }
        if($model->user_type=='Consolidator'){?>
        <br>
        <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#collapseAddPackagesSell" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('common', 'Consolidator'); ?>
        </button>
        <br><br>
        <b><?= Yii::t('common', 'Date Start') ?>:</b> <?= $model->date_consolidator; ?><br><br>
        <b><?= Yii::t('common', 'Number of Deals') ?>:</b> <?= $num_deals; ?><br><br>
        <b><?= Yii::t('common', 'Earnings') ?>:</b> <?= $earnings; ?> руб.<br><br>
        <?php }?>
    </div>
</div>
</div>
