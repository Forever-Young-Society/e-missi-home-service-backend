<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\EmailQueue */

/* $this->title = $model->label() .' : ' . $model->name; */

?>
<style>
.content-view-section {
	margin-top: 60px;
	margin-bottom: 44px;
}

.content-view-section {
	border: 1px solid #ececec;
}

.content-view-section h1 {
	margin-top: 0;
	font-size: 25px;
	margin-bottom: 12px;
}

.bottom-content i {
	background: #6dbd63;
	color: #fff;
	padding: 4px 6px;
	border-radius: 50px;
}
</style>
<div class="wrapper">
	<div class="container">
		<div class="clearfix">
			<div class="offset-md-3 col-md-6">
				<div class="content-view-section clearfix card">

					<div class="card-body">
						<div class="email-list-view">
							<h1>Thank You</h1>
							<p>You have been successfully removed form this subscriber
								list.You will no longer hear from us</p>
							<hr />
							<div class="bottom-content">
								<p>
									<i class="fa fa-question"></i> Did you unsubscribe by accident?
									<a
										href="<?php

        echo Url::toRoute([
            'email-queue/subscribe',
            'id' => \Yii::$app->request->get('id')
        ]);
        ?>"
										class="content-link"> Click Here To re-subscribe</a>
								
								
								<hr />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

