<?php
use app\components\TDashboard;
?>
<div class="wrapper">

	<?php
echo TDashboard::widget([
    'items' => $items
]);
?>
</div>
