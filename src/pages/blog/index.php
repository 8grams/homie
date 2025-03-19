<?php
    $categories = $this->blog->getCategories();
    $tags = $this->blog->getTags();

    $selectedCategory = $_GET['category'] ?? '';
    $selectedTag = $_GET['tag'] ?? '';

    $queryParams = $_GET;
    if ($selectedCategory === '') {
        unset($queryParams['category']);
    }
    if ($selectedTag === '') {
        unset($queryParams['tag']);
    }

    if (count($queryParams) !== count($_GET)) {
        $queryString = http_build_query($queryParams);
        $url = strtok($_SERVER["REQUEST_URI"], '?');
        $redirectUrl = $queryString ? "$url?$queryString" : $url;
        header("Location: $redirectUrl");
        exit;
    }

    if (!empty($selectedCategory) && !empty($selectedTag)) {
        $blogs = $this->blog->getPostsByCategory($selectedCategory, ['tags' => [$selectedTag]]);
    } elseif (!empty($selectedCategory)) {
        $blogs = $this->blog->getPostsByCategory($selectedCategory);
    } elseif (!empty($selectedTag)) {
        $blogs = $this->blog->getPostsByTag($selectedTag);
    } else {
        $blogs = $this->blog->getPosts();
    }
?>


<main>
    <section class="highlights">
        <div class="header-container">
            <h2>Example Blog</h2>
            <form method="GET" action="" class="filters">
                <div class="filter-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category" class="filter-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>" <?= $selectedCategory == $category->getId() ? 'selected' : '' ?>>
                                <?= $category->getName() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="tag">Tag:</label>
                    <select name="tag" id="tag" class="filter-select">
                        <option value="">All Tags</option>
                        <?php foreach ($tags as $tag): ?>
                            <option value="<?= $tag->getId() ?>" <?= $selectedTag == $tag->getId() ? 'selected' : '' ?>>
                                <?= $tag->getName() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn" style="border: none">Apply</button>
            </form>
        </div>
        <div class="highlight-grid">
            <?php foreach ($blogs as $blog): ?>
                <div class="highlight blog">
                    <img src="<?= $blog->getImage() ?>" alt="<?= $blog->getTitle() ?>" class="blog-image">
                        <h3 class="blog-title"><?= $blog->getTitle() ?></h3>
                        <?= str_replace('<p>', '<p class="blog-desc">', $blog->getExcerpt()) ?>
                        <a href="/blog/<?= $blog->getSlug() ?>?blog_id=<?= $blog->getId() ?>&category=<?= $selectedCategory ?>&tag=<?= $selectedTag ?>" class="custom-btn">View Article</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .filters {
        display: flex;
        gap: 10px;
        align-items: end;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    .filter-group label {
        font-size: 14px;
        color: #586069;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .filter-select {
        padding: 8px 12px;
        border: 1px solid #d1d5da;
        border-radius: 6px;
        background: #fff;
        font-size: 14px;
        color: #24292e;
        cursor: pointer;
        transition: border 0.3s, padding-right 30px;
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="%2324292e"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 16px;
        padding-right: 30px;
    }
    .filter-select:hover {
        border-color: #0366d6;
    }
</style>
