<?php

/* @var $this yii\web\View */
/*
 * $this->title = 'Info';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */
?>
<div class="wrapper">
	<div class="card">
		<div class="log-create">
			<?=  \app\components\PageHeader::widget(['title' => 'System Info']); ?>
		</div>
	</div>
	<div class="card">
		<header class="card-header"> 
			  <?php echo strtoupper(Yii::$app->controller->action->id); ?> 
			</header>
		<div class="card-body">
			<ul class="nav nav-tabs info-tabs mt-5" role="tablist">
				<li class="nav-item"><a class="nav-link active" data-toggle="tab"
					href="#general">General</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab"
					href="#technical">Technical</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content mt-4">
				<div id="general" class="tab-pane active">
							<?php
    echo \app\components\TDetailView::widget([
        'model' => $model['Generic'],
        'options' => [
            'class' => 'table table-bordered table-striped '
        ]
    ]);
    ?>
						</div>
				<div id="technical" class="tab-pane fade">
					<br>
							<?php
    echo $model['Technical'];
    ?>
						</div>
			</div>
		</div>
	</div>
</div>