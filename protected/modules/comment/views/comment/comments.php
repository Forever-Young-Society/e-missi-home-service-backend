<?php
?>
<div class="card mx-3">
	<div class="card-body">
		<div class="content-list content-image menu-action-right">
			<ul class="list-wrapper pl-0">
            <?php
            echo \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider,

                'summary' => false,

                'itemOptions' => [
                    'class' => 'item'
                ],
                'itemView' => '_list',
                'options' => [
                    'class' => 'list-view comment-list'
                ]
            ]);
            ?>
            </ul>
		</div>
	</div>
</div>