<video:video>
    <video:thumbnail_loc><?= $this->e($video->thumbnailLoc) ?></video:thumbnail_loc>
    <video:title><?= $this->e($video->title) ?></video:title>
    <video:description><?= $this->e($video->description) ?></video:description>
<?php if ($video->contentLoc) : ?>
    <video:content_loc><?= $this->e($video->contentLoc) ?></video:content_loc>
<?php endif; ?>
<?php if ($video->playerLoc) : ?>
    <video:player_loc><?= $this->e($video->playerLoc) ?></video:player_loc>
<?php endif; ?>
<?php foreach($video->options as $tag => $value) : ?>
    <video:<?= $this->e($tag) ?>><?= $this->e($value) ?></video:<?= $this->e($tag) ?>>
<?php endforeach; ?>
<?php foreach($video->allow as $tag => $value) : ?>
    <video:<?= $this->e($tag) ?> relationship="allow"><?= $this->e($value) ?></video:<?= $this->e($tag) ?>>
<?php endforeach; ?>
<?php foreach($video->deny as $tag => $value) : ?>
    <video:<?= $this->e($tag) ?> relationship="deny"><?= $this->e($value) ?></video:<?= $this->e($tag) ?>>
<?php endforeach; ?>
<?php foreach($video->tags as $tag) : ?>
    <video:tag><?= $this->e($tag) ?></video:tag>
<?php endforeach; ?>
</video:video>
