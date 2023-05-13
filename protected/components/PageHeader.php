<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\models\User;
use Yii;
use yii\helpers\Inflector;

class PageHeader extends TBaseWidget
{

    public $title;

    public $subtitle;

    public $model;

    public $showToolbar = false;

    public $showAdd = true;

    public function init()
    {
        parent::init();
        if (isset(\Yii::$app->params['legacyToolbarEnabled']) && \Yii::$app->params['legacyToolbarEnabled']) {
            $this->showToolbar = true;
        }
    }

    public function run()
    {
        if ($this->title === null) {
            if ($this->model != null) {
                $this->title = (string) $this->model;
            } else {
                $id = str_replace('admin/', '', Yii::$app->controller->id);
                $this->title = Inflector::pluralize(Inflector::camel2words($id));
            }
        }
        if ($this->subtitle === null) {

            $this->subtitle = Inflector::camel2words(Yii::$app->controller->action->id);
        }
        $this->renderHtml();
    }

    public function renderHtml()
    {
        ?>


<div class="page-head">
	<h1><?php echo \yii\helpers\Html::encode($this->title);?></h1>

	<div class="head-content">
           <?php if ($this->model != null) echo $this->model->getStateBadge();?>
            <?php

        $class = 'app\modules\favorite\widgets\Favorite';

        if (class_exists($class) && yii::$app->hasModule('favorite')) {
            if ($this->model != null) {
                echo $class::widget([
                    'model' => $this->model
                ]);
            }
        }

        ?>
        
        <?php if($this->showToolbar):?>
			<div class="state-information">
		
		       <?php if (!User::isGuest())  echo \app\components\TToolButtons::widget(); ?>
				
		</div>
			<?php endif;?>
     </div>



</div>

<!-- panel-menu -->



<?php
    }
}