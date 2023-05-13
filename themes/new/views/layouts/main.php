<?php
use app\assets\AppAsset;
use app\components\EmailVerification;
use app\components\FlashMessage;
use app\components\TActiveForm;
use app\models\User;
use app\modules\notification\widgets\NotificationWidget;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;

/* @var $this \yii\web\View */
/* @var $content string */
// $this->title = yii::$app->name;

AppAsset::register($this);
?>
<?php
$this->beginPage()?>
<!DOCTYPE html>
<html lang="<?=Yii::$app->language?>">
<head>
<meta charset="<?=Yii::$app->charset?>" />
      <?=Html::csrfMetaTags ()?>
      <title><?=Html::encode ( $this->title )?></title>
      <?php
    $this->head()?>
      <meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link rel="shortcut icon"
	href="<?=$this->theme->getUrl ('img/favicon.ico')?>" type="image/png">
<link href="<?php echo $this->theme->getUrl('css/style-admin.css')?>"
	rel="stylesheet">
<link
	href="<?php echo $this->theme->getUrl('css/style-responsive.css')?>"
	rel="stylesheet">
<link href="<?php echo $this->theme->getUrl('css/font-awesome.css')?>"
	rel="stylesheet">
<link href="<?php echo $this->theme->getUrl('css/glyphicon.css')?>"
	rel="stylesheet">
<link
	href='<?php echo $this->theme->getUrl('fullcalendar/lib/main.min.css'); ?>'
	rel='stylesheet' />
<link
	href="<?php echo $this->theme->getUrl('css/jquery.fancybox.css')?>"
	rel="stylesheet">
</head>
<body
	class="sticky-header <?php echo Yii::$app->session->get('is_collapsed') ?>">
      <?php
    $this->beginBody()?>
      <section class="position-relative">
		<!-- sidebar left start-->
		<div class="sidebar-left  style-scroll">
			<!--responsive view logo start-->
			<div class="logo theme-logo-bg  d-block d-xl-none">
				<a href="<?= Url::home();?>" class="logo-hidden"> <img
					src="<?=$this->theme->getUrl ('img/forever.png')?>">
				</a> <a href="<?= Url::home();?>" class="logo-show text-white"> <img
					src="<?=$this->theme->getUrl ('img/forever.png')?>">
				</a>
			</div>
			<!--responsive view logo end-->
			<div class="sidebar-left-info">
				<!-- visible small devices start-->
				<div class=" search-field"></div>
				<!-- visible small devices start-->
				<!--sidebar nav start-->
               <?php
            if (method_exists($this->context, 'renderNav')) {
                ?>
               <?= Menu::widget ( [ 'encodeLabels' => false,'activateParents' => true,'items' => $this->context->renderNav (),'options' => [ 'class' => 'nav  nav-stacked side-navigation' ],'submenuTemplate' => "\n<ul class='child-list'>\n{items}\n</ul>\n" ] );?>
               <?php
            }
            ?>
               <!--sidebar nav end-->
			</div>
		</div>
		<!-- sidebar left end-->
		<!-- body content start-->
		<div class="body-content">
			<!-- header section start-->
			<div class="header-section bg-success">
				<!--logo and logo icon start-->
				<div class="logo theme-logo-bg d-xl-block d-none">
					<a href="<?= Url::home();?>" class="logo-hidden"> <img
						src="<?=$this->theme->getUrl ('img/forever.png')?>">

					</a> <a href="<?= Url::home();?>" class="logo-show text-white"> <img
						src="<?=$this->theme->getUrl ('img/forever.png')?>">
					</a>
				</div>
				<!--logo and logo icon end-->
				<!--toggle button start-->

				<!--toggle button end-->
				<!--mega menu start-->
				<div class='pull-left'>
					<a class="toggle-btn"><i class="fa fa-bars" aria-hidden="true"></i></a>
				</div>

				<div class="pull-right d-flex align-items-center gap-3 flex-wrap">
			
					
				<?php
    $model = new User();
    $form = TActiveForm::begin([
        'id' => 'date-range-form',
        'action' => Url::to([
            '/user/set-daterange'
        ]),
        'method' => "post",
        'enableAjaxValidation' => false,
        'enableClientValidation' => false
    ]);
    ?>


					
                            <?php
                            $model->from_date = $model->getDateRange();
                            echo $form->field($model, 'from_date', [
                                'options' => [
                                    'class' => 'drp-container'
                                ]
                            ])
                                ->widget(DateRangePicker::classname(), [
                                'name' => 'to_date',
                                'pickerIcon' => '<i class="fa fa-calendar"></i>',
                                'presetDropdown' => true,
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'includeMonthsFilter' => true,
                                'pluginOptions' => [
                                    'locale' => [
                                        'format' => 'd-M-y',
                                        'separator' => ' to '
                                    ]
                                ],
                                'options' => [
                                    'placeholder' => 'Select range...'
                                ]
                            ])
                                ->label(false);
                            ?>
                          

<?php TActiveForm::end(); ?>
				
				<!--mega menu end-->
					<div class="notification-wrap">

						<!--right notification start-->
						<div class="right-notification">


							<ul class="notification-menu">
								<li class="mr-4">
										<?php echo NotificationWidget::widget();?>
							</li>
								<li><a href="javascript:;"
									class="dropdown-toggle align-items-center"
									data-toggle="dropdown"> <span class="adm-icn"> 
										<?php
        if (! empty(Yii::$app->user->identity->profile_file) && is_file(UPLOAD_PATH . Yii::$app->user->identity->profile_file)) {
            echo Html::img(Yii::$app->user->identity->getImageUrl(), [
                'class' => 'img-responsive'
            ]);
        } else {
            echo Html::img($this->theme->getUrl('img/default.jpg'), [
                'class' => 'img-responsive'
            ]);
        }
        ?>
								</span>
                          
                           <?php
                        echo substr(Yii::$app->user->identity->full_name, 0, 15);
                        ?>
                           <span class=" fa fa-angle-down"></span>
								</a>
									<ul class="dropdown-menu dropdown-usermenu purple float-right">

										<li class="profile-detail-card">
											<div class="profile-top-blk">
												<span class="profile-hd-img"> <?php
            if (! empty(Yii::$app->user->identity->profile_file) && is_file(UPLOAD_PATH . Yii::$app->user->identity->profile_file)) {
                echo Html::img(Yii::$app->user->identity->getImageUrl(), [
                    'class' => 'user-img-custom img-responsive'
                ]);
            } else {
                echo Html::img($this->theme->getUrl('img/default.jpg'), [
                    'class' => 'user-img-custom img-responsive'
                ]);
            }
            ?>
											</span>
												<div class="profile-hd-dtl">
													<h4 class="text-white mb-0"><?=substr(Yii::$app->user->identity->full_name,0,15)?></h4>

												</div>
											</div>
										</li>

										<li><a
											href="<?php
        echo Yii::$app->user->identity->getUrl();
        ?>"> <i class="fa fa-user float-right"></i> Profile
										</a></li>
										<li><a
											href="<?php
        echo Yii::$app->user->identity->getUrl('changepassword');
        ?>"> <span class="fa fa-key float-right"></span> <span>Change
													Password</span>
										</a></li>
										<li><a
											href="<?php
        echo Yii::$app->user->identity->getUrl('update');
        ?>"> <span class="fa fa-pencil float-right"></span> Update
										</a></li>
										<li><a
											href="<?php
        echo Url::toRoute([
            '/user/logout'
        ]);
        ?>"> <i class="fa fa-sign-out float-right"></i> Log Out
										</a></li>
                              <?php if( isset(Yii::$app->params['bug-report-link'])){?>
                              <li><a
											href="<?= Yii::$app->params['bug-report-link'];?>"> <i
												class="fa fa-sign-out float-right"></i> Report a Problem
										</a></li>
                              <?php }?>
                           </ul></li>
							</ul>
						</div>
						<!--right notification end-->
					</div>
				</div>
			</div>
			<!-- header section end-->
			<!-- page head start-->
            <?=Breadcrumbs::widget ( [ 'links' => isset ( $this->params ['breadcrumbs'] ) ? $this->params ['breadcrumbs'] : [ ] ] )?>
            <!--body wrapper start-->
			<section class="main_wrapper">
	
               <?= FlashMessage::widget()?>  
               
                <?= EmailVerification::widget() ?>
               <?=$content;?>
            </section>
			<footer>
				<div class="row">
					<div class="col-md-12 text-center">
						<p class="mb-0">&copy; <?php echo date('Y')?>  <a
								href="<?= Url::home();?>"><?=Yii::$app->name?></a> | All Rights
							Reserved.
						</p>
					</div>
				</div>
			</footer>
			<!--body wrapper end-->
		</div>
		<!-- body content end-->
	</section>
	<!--common scripts for all pages-->
	<script src="<?php echo $this->theme->getUrl ( 'js/scripts.js' )?>"></script>
	<script
		src='<?php echo $this->theme->getUrl('fullcalendar/lib/main.min.js');?>'></script>
	<script
		src='<?php echo $this->theme->getUrl('calendar/js/theme-chooser.js'); ?>'></script>
	<script
		src="<?php echo $this->theme->getUrl ( 'js/custom-modal.js' )?>"></script>
	<script>
         $(document).ready(function(){
         
           changeActiveClass();
         
           
           $(document).on('click', '.header-section .toggle-btn', function(){
             changeActiveClass();
           })
         
           function changeActiveClass() {
             if($('body').hasClass('sidebar-collapsed')) {
               var menu = $('.side-navigation .menu-list.active');
               if(menu.length >= 1){
                 menu.removeClass('active');
                 menu.addClass('inactive');
               }
             }else{
               var menu = $('.side-navigation .menu-list.inactive');
               if(menu.length >= 1){
                 menu.removeClass('inactive');
                 menu.addClass('active');
               }
             }
           }
         });
      </script>
	<script>
    $(".sub-menu-list > a").click(function(e){
        e.preventDefault();
        $('.sub-menu-list').removeClass('active');
        $(".sub-menu-list > ul").slideUp(300)
        if ( $(this).next("ul").is( ":hidden" ) ) {
            $(this).next("ul").slideDown(300)
            $(this).closest('.sub-menu-list').addClass('active');
        } else {
            $(this).next("ul").slideUp(300)
        }
    });
       </script>
	<script>
    $("#user-from_date").on("change", function(e) {
	  $( "#date-range-form" ).submit();
 	});
</script>
      <?php
    $this->endBody()?>
   </body>
   <?php
$this->endPage()?>
</html>
