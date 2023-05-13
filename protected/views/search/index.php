<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
use yii\widgets\Pjax;

$this->params['breadcrumbs'][] = [
	'label' => Yii::t('app', 'Dashboard')
];
?>

<div class="wrapper">

	<div class="card">
		<div class="card-body">
			Search for <strong>
				<?php echo $q; ?>
			</strong>

		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<div class="content-section">
				<?php Pjax::begin(['id'=>'search-pjax-ajax-tabs','enablePushState'=>false]); ?>			
				<?php
				$this->context->startPanel();
				foreach ($items as $item) {
					$this->context->addPanelUrl($item['title'], $item['url']);
				}
				$this->context->endPanel();
				?>
				<?php Pjax::end(); ?>
			</div>
		</div>
	</div>
</div>
