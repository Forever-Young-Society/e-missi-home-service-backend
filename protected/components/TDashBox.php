<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

class TDashBox extends TBaseWidget
{

    public $items = [];

    public function init()
    {
        parent::init();
        foreach ($this->items as &$item) {
            if (! isset($item['color'])) {
                $item['color'] = 'green';
            }
            if (! isset($item['visible'])) {
                $item['visible'] = true;
            }
            if (! isset($item['icon'])) {
                $item['icon'] = 'fa fa-users';
            }
        }
    }

    public function renderHtml()
    {
        ?>

<!--state overview start-->

<div class="row state-overview">
	<?php

        foreach ($this->items as $item) {

            if (! $item['visible'])
                continue;
            ?>
	
		<div class="col-md-4 col-lg-4 col-xl-3 col-sm-6">
		
		<?php // Dont make <a> tag if url not set
		if (isset($item['url'])) {  ?>
                <a href="<?php echo $item['url']?>">
                   <?php }?>
                   
			<section class=<?php echo $item['color']?>>
				<div class="symbol">
					<i class="<?php echo $item['icon']?>"></i>
				</div>
				<div class="value white">
					<h3 data-speed="1000" data-to="320" data-from="0" class="timer"><?php echo $item['data']?></h3>
					<p><?php echo $item['header']?></p>
				</div>
			</section>
		</a>
	</div>


<?php }?>
</div>

<?php
    }
}