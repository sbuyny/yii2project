<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use common\models\Licence;
?>
<div class="col-xs-12">
    <p>
    <h2> <?= Yii::t('common', 'Licences') ?>  


    </h2>
</p>
</div>
<?php
echo $this->render('/dashboard/dashboardTabs');
?>

<?php 
         Modal::begin([
            'header'=>'<h4>'.Yii::t('frontend', 'Get Brokers Licences').'</h4>',
            'id'=>'modal_window2',
         ]);

        $form = ActiveForm::begin([
                'id' => $model->formName(),
 
         ]);
        ?>
        <input type="hidden" name="add_consolidator_request" value="1">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('frontend', 'Parameter buy') ?></div>
            <div class="panel-body">

                <?= GridView::widget([
                    'dataProvider' => $licence_prices,
                    'columns' => [
                        'minimal_number_licences',
                        'maximum_number_licences',
                        'price',
                    ],
                ]); ?>
                <div class="input-group input-group-sm row">
                    <div class="col-xs-12">       
                        <div class="row">
                            <div class="col-xs-12">     
                                <?php echo $form->field($model, 'number_licences',['options'=>['class'=>'required']])->textInput(); ?>
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
        <br>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modal_window2" type="button" data-toggle="collapse" data-target="#collapseAddPackagesSell" aria-expanded="false" aria-controls="collapseAddPackagesSell">
            <?= Yii::t('frontend', 'Get Brokers Licences'); ?>
        </button>
        <br><br>
<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
            [
                'attribute' => 'licence_number',
                'options' => ['width' => '30'],
            ],
            [
                'attribute' => 'owner_id',
                'label' => Yii::t('frontend', 'Broker'),
                'options' => ['width' => '50'],
                'format'=>'raw',
                'value'=>function ($data) {
                    $broker=$data->broker_id;
                    if(isset($broker) && $broker>0){
                        $username=common\models\User::findOne($broker)->username;
                        return $username;
                    }
                },

            ],
            'price',
            'procent',
            [
                'attribute' => 'date_start',
                'value'=>function ($data) {
                    if($data->date_start){
                    return date('Y-m-d',$data->date_start);
                    }
                },
            ],
            [
                'attribute' => 'date_finish',
                'value'=>function ($data) {
                    if($data->date_finish){
                    return date('Y-m-d',$data->date_finish);
                    }
                },
            ],
            [
                'attribute' => 'date_start',
                'options' => ['width' => '50'],
                'format'=>'raw',
                'label' => Yii::t('backend', 'Status'),
                'value'=>function ($data) {
                    if(!$data->date_start && !$data->date_finish && !$data->licence_number){
                    return Html::tag('span', Yii::t('common', 'Moderation'), ['style' => ['color' => '#f0ad4e']]);
                    }
                    if(!$data->date_start && $data->date_finish && !$data->licence_number){
                    return Html::tag('span', Yii::t('common', 'Cancelled'), ['style' => ['color' => '#d9534f']]);
                    }
                    if($data->date_start>0 && ($data->date_finish==0 || $data->date_finish > time())){
                    return Html::tag('span', Yii::t('common', 'Working'), ['style' => ['color' => '#5cb85c']]);
                    }
                    if($data->date_finish<time() && $data->licence_number){
                    return Html::tag('span', Yii::t('common', 'Finished'), ['style' => ['color' => '#d9534f']]);
                    }
                },
            ],           
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{stop}',
                'buttons' => [
                    'stop' => function ($url,$data) {
                        if(isset($data->broker_id) && $data->broker_id>0)return Html::a(
                        '<span class="glyphicon glyphicon-remove" title="'.Yii::t('common', 'Stop licence').'"></span>', 
                        '?stop='.$data->id, [
                                        'data-confirm' => Yii::t('yii', Yii::t('frontend', 'Are you sure you want to stop that licence?')),
                                ]
                        );
                    },
                ],
            ],
    ],
]);
?>
        
     
        
<?php 
        $form = ActiveForm::begin([
                'id' => $user_search->formName(),
 
         ]);
        ?>
        <input type="hidden" name="search_users" value="1">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('frontend', 'Search users') ?></div>
            <div class="panel-body">
                <div class="input-group input-group-sm row">
                    <div class="col-xs-12">       
                        <div class="row">
                            <div class="col-xs-4">     
                                <?php echo $form->field($user_search, 'username')->textInput(); ?>
                            </div> 
                            <div class="col-xs-4">     
                                <?php echo $form->field($user_search, 'fio')->textInput(); ?>
                            </div>
                            <div class="col-xs-4" style="padding-top:24px;">     
                                <?= Html::submitButton(Yii::t('frontend', 'Search users'), ['class' => 'btn btn-info', 'name' => 'signup-button']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end();?>
  
        
        
        
<?php 
         Modal::begin([
            'header'=>'<h4>'.Yii::t('common', 'Give licence').': <span id="add_broker_licence_login"></span></h4>',
            'id'=>'modal_window3',
         ]);

        $form_licence_broker = ActiveForm::begin([
                'id' => $licence_broker->formName(),
 
         ]);
        ?>
        <input type="hidden" name="add_broker_licence" value="1">
        <input type="hidden" id="add_broker_licence_id" name="LicenceBrokerForm[broker_id]" value="0">
        <div class="panel panel-default">
            <div class="panel-heading"><?= Yii::t('frontend', 'Parameter buy') ?></div>
            <div class="panel-body">
                <div class="input-group input-group-sm row">
                    <div class="col-xs-12">       
                        <div class="row">
                            <div class="col-xs-12">     
                                <?php echo $form_licence_broker->field($licence_broker, 'procent',['options'=>['class'=>'required']])->textInput(); ?>
                            </div> 
                            <div class="col-xs-12">     
                                <?= $form->field($licence_broker, 'licence_id')->dropDownList(array(''=>'') + Licence::getLicenceList()) ?>
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
       
        
  
<?=
GridView::widget([
    'dataProvider' => $users,
    'columns' => [
            [
                'attribute' => 'username',
                'label' => Yii::t('frontend', 'Username'),
            ],
            [
                'attribute' => 'fio',
                'label' => Yii::t('backend', 'FIO'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{add_licence}',
                'buttons' => [
                    'add_licence' => function ($url,$data) {
                        return Html::a(
                        '<span class="glyphicon glyphicon-list-alt" title="'.Yii::t('common', 'Give licence').'"></span>', 
                        '', [
                        'data' => [
                            'target' => '#modal_window3',
                            'toggle' => 'modal',
                            
                        ],
                        'onclick' => 'js:document.getElementById("add_broker_licence_id").value="'.$data->id.'";document.getElementById("add_broker_licence_login").innerHTML="'.$data->username.'";',
                ]);
                        
                    },
                ],
            ],
    ],
]);
?>
<div>


</div>