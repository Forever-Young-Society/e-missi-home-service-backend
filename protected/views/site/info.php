<?php

/* @var $this yii\web\View */
/*
 * $this->title = 'About';
 * $this->params ['breadcrumbs'] [] = $this->title;
 */
?>
<section class="pagetitle-section">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<h1 class="mb-0 mt-0">System Info</h1>
			</div>
		</div>
	</div>
</section>
<section>
	<div class="container-fluid py-2">
		<div class="card">
			<div class="card-body">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item"><a class="nav-link active" data-toggle="tab"
						href="#general">General</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab"
						href="#technical">Technical</a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<div id="general" class="container tab-pane active">
						<br>
				<?php
    echo \app\components\TDetailView::widget([
        'model' => $model['Generic'],
        'options' => [
            'class' => 'table table-bordered'
        ]
    ]);
    ?>
			</div>
					<div id="technical" class="container tab-pane fade">
						<br>
						<div class="table-responsive">
<?php
echo $model['Technical'];
?>
</div>
			</div>
				</div>
			</div>
		</div>
	</div>
</section>