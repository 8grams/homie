<?php
/**
 * @var \App\Libs\Sitemap\Tags\Image $tag
 */
?>
<image:image>
<?php if (!empty($tag->url)) : ?>
    <image:loc><?= $this->e($tag->url) ?></image:loc>
<?php endif; ?>
<?php if (!empty($tag->caption)) : ?>
    <image:caption><?= $this->e($tag->caption) ?></image:caption>
<?php endif; ?>
<?php if (!empty($tag->geo_location)) : ?>
    <image:geo_location><?= $this->e($tag->geo_location) ?></image:geo_location>
<?php endif; ?>
<?php if (!empty($tag->title)) : ?>
    <image:title><?= $this->e($tag->title) ?></image:title>
<?php endif; ?>
<?php if (!empty($tag->license)) : ?>
    <image:license><?= $this->e($tag->license) ?></image:license>
<?php endif; ?>
</image:image>
