<?php
namespace app\modules\smtp\widgets;

use app\components\TActiveForm;
use app\components\TBaseWidget;
use yii\helpers\Html;
use app\modules\smtp\models\Unsubscribe;

/**
 * Widget to allow selecting and adding contacts
 *
 * @author shivc
 *        
 */
class UnsubscribeSelect extends TBaseWidget
{

    public $email;

    public $model;

    public function init()
    {
        parent::init();

        if (empty($this->email)) {
         
            if ($this->model && $this->model->hasAttribute('email')) {
                $this->email = $this->model->email;
            }
        }
    }

    public function renderHtml()
    {
        ?>

<div class="card">
	<div class="card-header">
		<h4>Unsubscribe</h4>
	</div>
	<div class="card-body">
        <?php
        TActiveForm::begin([
            'id' => 'unsubscribe-select-form',
            'layout' => 'inline'
        ]);
        ?>
			<div class="row col-md-12">
			<div class="col-md-6">
		<?php if($this->email){?>
		    <b><?php echo $this->email?><br>
				<span class="text-danger"><?php echo (Unsubscribe::check($this->email)) ?  'Blacklisted' : '';?></span></b>
			</div>
			<div class="col-md-6">
			
			<?php  if ( !Unsubscribe::check($this->email)) {?>
			<?= Html::a('Add to Unsubscribe', ['/smtp/unsubscribe/select-unsubscribe', 'email' => $this->email], ['class' => 'btn btn-primary']) ?>
        <?php
            } else {
                echo Html::a('Remove to Unsubscribe', [
                    '/smtp/unsubscribe/delete-unsubscribe',
                    'email' => $this->email
                ], [
                    'class' => 'btn btn-primary'
                ]);
            }
            ?>
        </div>
        <?php }?>
		</div>
<?php
        TActiveForm::end();
        ?></div>
</div><?php
    }
}