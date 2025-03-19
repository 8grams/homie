<?php
    $blog_id = $_GET['blog_id'] ?? null;
    $blog = $this->blog->getPostById($blog_id);
?>

<main>
    <section style="margin-top: 20px">
        <img src="<?= $blog->getImage() ?>" alt="<?= $blog->getTitle() ?>" style="width: 100%">
        <h1><?= $blog->getTitle() ?></h1>
        <?= $blog->getContent() ?>
    </section>
</main>