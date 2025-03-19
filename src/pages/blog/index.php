<?php
    $blogs = $this->blog->getPosts();
?>

<main>
    <section class="highlights">
        <h2>Example Blog</h2>
        <div class="highlight-grid">
            <?php foreach ($blogs as $blog): ?>
                <div class="highlight blog">
                    <img src="<?= $blog->getImage() ?>" alt="<?= $blog->getTitle() ?>" class="blog-image">
                    <h3 class="blog-title"><?= $blog->getTitle() ?></h3>
                    <?= str_replace('<p>', '<p class="blog-desc">', $blog->getExcerpt()) ?>
                    <a href="/blog/<?= $blog->getSlug() ?>?blog_id=<?= $blog->getId() ?>">View Article</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>