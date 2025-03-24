<?php
/**
 * @var \App\Libs\Sitemap\Tags\News $tag
 */
?>
<news:news>
    <news:publication>
        <news:name><?= $this->e($tag->name) ?></news:name>
        <news:language><?= $this->e($tag->language) ?></news:language>
    </news:publication>
    <news:title><?= $this->e($tag->title) ?></news:title>
    <news:publication_date><?= $this->e($tag->publicationDate->toW3cString()) ?></news:publication_date>
<?php foreach($tag->options as $key => $value) : ?>
    <news:<?= $this->e($key) ?>><?= $this->e($value) ?></news:<?= $this->e($key) ?>>
<?php endforeach; ?>
</news:news>