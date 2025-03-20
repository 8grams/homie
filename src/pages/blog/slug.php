<?php
    $blog_id = $_GET['blog_id'] ?? null;
    $selectedCategory = $_GET['category'] ?? null;
    $selectedTag = $_GET['tag'] ?? null;

    $blog = $this->blog->getPostById($blog_id);
    $relatedPosts = [];

    if ($selectedCategory) {
        $relatedPosts = $this->blog->getPostsByCategory($selectedCategory, ['per_page' => 3]);
    }

    if (empty($relatedPosts) && $selectedTag) {
        $relatedPosts = $this->blog->getPostsByTag($selectedTag, ['per_page' => 3]);
    }

    if (empty($relatedPosts)) {
        $tags = $blog->getCategories();
        $tag_ids = array_map(fn($tag) => (int) $tag->getId(), $tags);
        if (!empty($tag_ids)) {
            $tag_id = reset($tag_ids);
            $relatedPosts = $this->blog->getPostsByCategory($tag_id, ['per_page' => 3]);
        }
    }

    $currentPostId = $blog->getId();
    $relatedPosts = array_values(array_filter($relatedPosts, fn($post) => $post->getId() !== $currentPostId));

    if (count($relatedPosts) == 0) {
        $relatedPosts = [];
    }

    $blog->setRelatedPosts($relatedPosts);
    $related = $blog->getRelatedPosts();
?>


<main>
    <section style="margin: 20px 0">
        <img src="<?= $blog->getImage() ?>" alt="<?= $blog->getTitle() ?>" style="width: 100%">
        <h1><?= $blog->getTitle() ?></h1>
        <?= $blog->getContent() ?>
    </section>
    <section class="highlights">
        <h1 class="text-center">Related Post</h1>
        <div class="related-posts">
            <?php foreach ($related as $blog): ?>
                <div class="post">
                    <img src="<?= $blog->getImage() ?>" alt="<?= $blog->getTitle() ?>" class="blog-image">
                    <h3 class="blog-title"><?= $blog->getTitle() ?></h3>
                   <?= str_replace('<p>', '<p class="blog-desc">', $blog->getExcerpt()) ?>
                    <a href="/blog/<?= $blog->getSlug() ?>?blog_id=<?= $blog->getId() ?>">View Article</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
    .related-posts {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        padding: 20px 0;
    }

    .post {
        width: 300px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        padding: 1rem;
    }

    .blog-image {
        max-height: 300px;
        width: 100%;
        display: block;
        margin: 0 auto;
        object-fit: cover;
    }

    .blog-title {
        color: black !important;
        font-size: 20px;
        margin-top: 20px !important;
        margin-bottom: 0 !important;
    }

    .blog-desc {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 14px;
        color: #555;
    }

    @media (max-width: 768px) {
        .related-posts {
            justify-content: space-around;
        }
    }

    @media (max-width: 480px) {
        .post {
            width: 100%;
        }
    }
</style>

