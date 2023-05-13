<?php

echo '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
?>
<sitemapindex xmlns="https://www.google.com/schemas/sitemap/0.84">
	
    <?php foreach ($urls as $url){ ?>
        <sitemap> <loc><![CDATA[<?= $url['loc'] ?>]]></loc>
            <?php if (isset($url['lastmod'])){ ?>
                <lastmod><?=is_string ( $url ['lastmod'] ) ? $url ['lastmod'] : date ( DATE_W3C, $url ['lastmod'] )?></lastmod>
            <?php } ?>
         
        </sitemap>
    <?php } ?>
</sitemapindex>
