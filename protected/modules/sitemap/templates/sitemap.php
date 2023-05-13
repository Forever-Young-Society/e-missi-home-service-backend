<?php
use app\modules\sitemap\Module;

echo '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
	xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
	
    <?php foreach ($urlQuery->each() as $url){ ?>
        <url> <loc><![CDATA[<?= $url->location ?>]]></loc>
            <?php if (isset($url->updated_on)){ ?>
                <lastmod><?=date ( DATE_W3C, strtotime($url->updated_on))?></lastmod>
            <?php } ?> 
            <changefreq><?= $url->getChangeFrequency()?></changefreq> <priority><?= $url->getPriority()?></priority>
</url> <?php }  ?> 
</urlset>
