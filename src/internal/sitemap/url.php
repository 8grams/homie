<url>
    <?php if (!empty($tag->url)) : ?>
    <loc><?= $this->e($tag->url) ?></loc>
    <?php endif; ?>
<?php if (count($tag->alternates)) : ?>
<?php foreach ($tag->alternates as $alternate) : ?>
    <xhtml:link rel="alternate" hreflang="<?= $this->e($alternate->locale) ?>" href="<?= $this->e($alternate->url) ?>" />
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($tag->lastModificationDate)) : ?>
    <lastmod><?= $this->e($tag->lastModificationDate->format(DateTime::ATOM)) ?></lastmod>
<?php endif; ?>
<?php if (!empty($tag->changeFrequency)) : ?>
    <changefreq><?= $this->e($tag->changeFrequency) ?></changefreq>
<?php endif; ?>
    <?php foreach ($tag->images as $image) : ?>
        <?php echo $this->insert('image', ['image' => $image]) ?>
    <?php endforeach; ?>
    <?php foreach ($tag->videos as $video) : ?>
        <?php echo $this->insert('video', ['video' => $video]) ?>
    <?php endforeach; ?>
    <?php foreach ($tag->news as $news) : ?>
        <?php echo $this->insert('news', ['news' => $news]) ?>
    <?php endforeach; ?>
</url>
