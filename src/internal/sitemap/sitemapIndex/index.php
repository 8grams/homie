<?= '<'.'?'.'xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach($tags as $tag) : ?>
    <?php echo $this->insert('/sitemapIndex/' . $tag->getType(), ['tag' => $tag]) ?>
<?php endforeach; ?>
</sitemapindex>
