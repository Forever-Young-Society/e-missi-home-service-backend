<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use app\models\User;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;

class EmailVerification extends TBaseWidget
{

    public function init()
    {
        parent::init();

        if (User::isAdmin() || Yii::$app->user->identity->email_verified) {
            $this->visible = false;
        }
    }

    public static function checkIfVerified()
    {
        if (Yii::$app->user->identity->email_verified) {
            return true;
        }
        if (in_array(\Yii::$app->controller->id, [

            'log',
            'site',
            'user',
            'dashboard'
        ])) {
            return true;
        }

        throw new HttpException(403, Yii::t('app', 'You are not allowed to access this page. Please verify your email to proceed.'));
    }

    function renderHtml()
    {
        ?>

<div class="wrapper">

	<div class="card verfication-card">

		<header class="card-header email-verfication">
			<h3>Email Verification</h3>
		</header>
		<div class="card-body">
			<div class="row align-items-center">
				<div class="col-md-8">
					<p>
                           <b> We have sent a verification mail to <a
							href="mailto:<?= \Yii::$app->user->identity->email ?>"><?= \Yii::$app->user->identity->email ?></a>.
							Please check your Inbox or spam. If	you have not received the email,  click  <?=  Html::a(("Resend"),Url::toRoute('user/email-resend'),['class'=>'btn btn-primary']); ?>
					</b>
					</p>
					<p>
                     <b> If	you wish to change the email,  click <?=  Html::a(("Update Profile"),['user/update', 'id'=>\Yii::$app->user->id],['target' =>'_blank','class'=>'btn btn-primary']); ?>
			        </b>
					</p>

				</div>

			</div>
		</div>
	</div>

</div>

<?php
    }
}
    
    

