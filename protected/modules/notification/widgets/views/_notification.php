<?php
use yii\helpers\Url;
?>
<li class="<?= $class ?>" id="<?= $id ?>"><a class="color-bell"
	data-toggle="dropdown" href="javascript:;" class="mega-link"> <span
		class="mega-icon"><i class="fa fa-bell"></i></span><sup> <span
			class="badge bg-danger notiCount-<?= $id ?>"><?=$count?></span></sup>
</a>
	<ul
		class="dropdown-menu notification-dropdown mailbox animated bounceInDown notification-menu-container">
		<li>
			<div class="drop-title" id="noti_count">
				You have <span class="notiCount-<?= $id ?>"><?=$count?></span> new
				notifications
			</div>
		</li>
		<li class="notification-pad">
			<div class="message-center-<?= $id ?>"></div>
		</li>
		<li><a class="text-center"
			href="<?= Url::toRoute(['/notification']) ?>"> <strong>See all
					notifications</strong> <i class="fa fa-angle-right"></i>
		</a></li>
		<li><a class="text-center"
			href="<?= Url::toRoute(['/notification/notification/read']) ?>"> <strong>Clear
					all notifications</strong> <i class="fa fa-angle-right"></i>
		</a></li>
	</ul></li>
<!-- /.dropdown-messages -->