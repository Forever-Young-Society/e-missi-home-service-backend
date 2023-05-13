<div class="card notice-view">
   <div class="card-header">
      Notices
   </div>
   <?php //Pjax::begin(['id'=>'notices']); ?>
   <div id='notices' class="card-body ">
      <div class="content-list content-image menu-action-right">
         <ul class="list-wrapper">
            <?php
               echo \yii\widgets\ListView::widget([
                   'dataProvider' => $notices,
                   
                   // 'summary' => false,
                   
                   'itemOptions' => [
                       'class' => 'item'
                   ],
                   'itemView' => '_view',
                   'options' => [
                       'class' => 'list-view notice-list'
                   ]
               ]);
               ?>
         </ul>
      </div>
   </div>
   <?php //Pjax::end(); ?>
</div>