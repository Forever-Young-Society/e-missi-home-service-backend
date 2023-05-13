<div class="card">

	<!-- todo: change image url with php code -->
	<div class="card-header">
		<h2 class="text-center">Setup Wizard</h2>
	</div>

	<div class="card-body  text-center">

		<p class="lead">Welcome to the Application Installer</p>

		<p>
			This wizard will install and configure your application.<br>
			<br>To continue, click Next.
		</p>

		<div class="text-center">
			<br />
			<?=\yii\helpers\Html::a ( "Next" . ' <i class="fa fa-arrow-circle-right"></i>', [ 'go' ], [ 'class' => 'btn btn-lg btn-primary' ] )?>
			<br />
			<br />
		</div>
	</div>


</div>