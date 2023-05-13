<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
use yii\helpers\Url;
use yii\helpers\Html;

?>
<script type="text/javascript"
	src="<?=$this->theme->getUrl('json-viewer/jquery.json-viewer.js')?>"></script>
<link
	href="<?=$this->theme->getUrl('json-viewer/jquery.json-viewer.css')?>"
	type="text/css" rel="stylesheet">


<div class='container-fluid'>
	<div class="row">
		<div class="col-md-6">
			<div class='api-list'>

                <?php

                foreach ($models as $class => $model) {

                    echo Html::tag('h1', $class);
                    echo $this->context->renderPartial('_api', [
                        'model' => $model,
                        'class' => $class
                    ]);
                }

                ?>
            </div>

		</div>
		<div class="col-md-6">
			<div class="bottom-file">
				<!--<div class="jquery-script-ads" style="margin:30px auto">
             </div> -->
				<div id="copySuccess" class="alert alert-danger my-1" role="alert">
					Text Copied!</div>
				<div class="bottom-file-inner">
					<pre id="json-renderer" style="width: 100%; height: 500px">

    </pre>
					<i class="fa fa-close"></i>
					<div class="row bg-blue mr0">
						<div class="col-sm-10">
							<pre id="json-display"></pre>
						</div>
						<div class="col-md-2 mt-4 text-right">
							<span id="copyBtn" class="btn btn-sm btn-info">Copy</span>
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
</div>
