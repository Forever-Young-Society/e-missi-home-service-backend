<?php
if ($count['count'] > 0) {
    $width = $total / $count['count'] * 100;
} else {
    $width = 0;
}

?>
<div class="d-flex">
	<div class="" style="width: 15%; line-height: 1;">
		<div style="height: 9px; margin: 5px 0;">
			<?=$count['rate_no']?> <span class="glyphicon glyphicon-star"></span>
		</div>
	</div>
	<div class="" style="width: 60%;">
		<div class="progress" style="height: 9px; margin: 8px 0;">
			<div class="progress-bar progress-bar-success" role="progressbar"
				aria-valuenow="5" aria-valuemin="0" aria-valuemax="5"
				style="width: <?=$width?>%">
				<span class="sr-only"><?=$width?>% Complete (danger)</span>
			</div>
		</div>
	</div>
	<div class="pull-right" style="margin-left: 10px;">
				<div class="badge badge-info"><?=$count['count']?></div>
				</div>
</div>