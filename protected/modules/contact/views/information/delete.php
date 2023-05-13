<?php
   use app\components\useraction\UserAction;
   use app\modules\comment\widgets\CommentsWidget;
   use yii\helpers\Html;
   use app\components\TActiveForm;
   /* @var $this yii\web\View */
   /* @var $model app\modules\contact\models\Information */
   
   /*$this->title =  $model->label() .' : ' . $model->id; */
   $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Informations'), 'url' => ['index']];
   $this->params['breadcrumbs'][] = (string)$model;
   ?>
<div class="wrapper">
   <div class="card">
      <div class="information-view card-body">
         <h4 class="text-danger">Are you sure you want to delete this item? All related data is deleted</h4>
         <?php echo  \app\components\PageHeader::widget(['model'=>$model]); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <?php echo \app\components\TDetailView::widget([
            'id'  => 'information-detail-view',
               'model' => $model,
               'options'=>['class'=>'table table-bordered'],
               'attributes' => [
                   'id',
                   'full_name',
                   'email:email',
                   'subject',
                   /*'description:html',*/
                   'address',
                   'mobile',
                   'landline',
                   'skype_id',
                   'website',
                   [
            'attribute' => 'state_id',
            'format'=>'raw',
            'value' => $model->getStateBadge(),],
                   [
            'attribute' => 'type_id',
            'value' => $model->getType(),
            ],
                   'created_on:datetime',
                   [
            'attribute' => 'created_by_id',
            'format'=>'raw',
            'value' => $model->getRelatedDataLink('created_by_id'),
            ],
               ],
            ]) ?>
         <?php  echo $model->description;?>
         <?php 
            $form = TActiveForm::begin([
                      
                        'id'  => 'information-form',
                        'options'=>[
                          'class'=>'row'
                        ]
                        ]);?>
         <div class="col-md-12">
            <?php echo $form->errorSummary($model); 
               ?>
         </div>
         <div class="col-md-12 mt-2 text-right">
            <?= Html::submitButton('Confirm', ['id'=> 'information-form-submit','class' =>'btn btn-success']) ?>
         </div>
         <?php TActiveForm::end(); ?>
      </div>
   </div>
   <div class="card">
      <div class="card-body">
         <div
            class="information-panel">
            <?php
               $this->context->startPanel();
                $this->context->addPanel('Activities', 'feeds', 'Feed',$model /*,null,true*/);
               
               $this->context->endPanel();
               ?>
         </div>
      </div>
   </div>
   <?php echo CommentsWidget::widget(['model'=>$model]); ?>
</div>