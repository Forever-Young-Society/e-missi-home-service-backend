<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use yii\helpers\Html;

class TAjaxDashBox extends TBaseWidget
{

    public $label;

    public $url;

    public function init()
    {
        parent::init();

        $this->options['id'] = $this->getId();
    }

    public function renderHtml()
    {
        $this->view->registerJs("$('#" . $this->id . "').load('" . $this->url . "');");

        ?>
<div class="card">


	<div class="card-body">
            	
      <?php echo Html::tag('div', 'Loading', $this->options);?>
            	
      </div>
</div>
<?php
    }
}

