
<?php
foreach ($addressQuery->each() as $add) {
    ?>
<div class="<?= isset($q) ? 'col-lg-12 col-md-12 global-col mb-4 mb-md-0 p-0' : 'col-lg-4 col-md-5 global-col mb-4 mb-md-0 p-0'?>">
	<div class="global-box">
		<div class="d-flex align-items-center">
			<img src="<?= $bundle->baseUrl . '/img/' .$add->country.'.svg'?>"
				alt="image" class="mr-2" width="24px" />
			<h3 class="mb-0"><?= $add->title?></h3>
		</div>

		<address><?= $add->address?></address>

		<ul>
						<?php
    $sales = $add->activeContacts;
    foreach ($sales as $sale) {
        if ($sale->hasProperty('contact_no')) {
            ?>
							<li><?= $sale->title?> :<b><?= $sale->getContactLink()?></b>
							<?php if($sale->toll_free_enable){?>
							<span class="toll-free-ic">(Toll-Free)</span>
									<?php }?>
							<?php  if($sale->whatsapp_enable){?>
							 <span class="what-app-ic"><a
					href="<?= $sale->getWhatsappLink()?>"> <img
						src="<?= $bundle->baseUrl . '/img/whatsapp.png'?>" alt="Whatsapp"
						width="20px"></a></span>
									
									<?php }if($sale->telegram_enable){?>
									 <span class="what-app-ic"><a
					href="<?= $sale->getTelegramLink()?>"> <img
						src="<?= $bundle->baseUrl . '/img/telegram.png'?>" alt="Telegram"></a></span>
									<?php }?>
							</li>
							<?php }}?>
						</ul>
                <?php if($add->latitude !== '0'){?>
						<a
			href="http://maps.google.com/?q=<?=$add->latitude?>,<?=$add->longitude?>"
			class="d-block ">View On Map </a>
              <?php }?>
					</div>
</div>
<?php }?>