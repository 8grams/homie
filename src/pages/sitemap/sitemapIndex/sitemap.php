<sitemap>
    <?php if (!empty($tag->url)) : ?>
    <loc><?= $this->e($tag->url) ?></loc>
    <?php endif; ?>
    <?php if (!empty($tag->lastModificationDate)) : ?>
    <lastmod><?= $this->e($tag->lastModificationDate->format(DateTime::ATOM)) ?></lastmod>
    <?php endif; ?>
</sitemap>
