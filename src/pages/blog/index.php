<?php
    $highlights = $this->blog->getPosts(['per_page' => 3]);
?>

<?php $this->start('components/tag') ?>
<title>Blogs</title>
<?php $this->stop() ?>
<section>
    <div>
        <div>
            <?php if (!empty($highlights)): ?>
                <?php $firstHighlight = $highlights[0]; ?>
                <a href="/blog/<?= $firstHighlight->getSlug() ?>?blog_id=<?= $firstHighlight['id'] ?>" aria-label="See detail <?= $firstHighlight['title']['rendered'] ?>" class="w-full md:w-3/5">
                    <img src="<?= $firstHighlight['_embedded']['wp:featuredmedia'][0]['source_url'] ?>" alt="<?= $firstHighlight['title']['rendered'] ?>" class="bg-cover w-full md:h-[410px] h-[145px] mb-3 object-cover">
                    <h1><?= $firstHighlight['title']['rendered'] ?></h1>
                    <p>
                        <?php
                            if (isset($firstHighlight['_embedded']['wp:term'][1])) {
                                $tags = array_map(function ($tag) {
                                    return $tag['name'];
                                }, $firstHighlight['_embedded']['wp:term'][1]);
                                echo implode(', ', $tags);
                            }
                        ?>
                        <span>&#8226;</span>
                        <?= date("d M Y", strtotime($firstHighlight['date'])) ?>
                    </p>
                    <p>
                        <?= strip_tags($firstHighlight['excerpt']['rendered']) ?>
                    </p>
                </a>
            <?php endif; ?>
            <div>
                <div>
                    <?php foreach (array_slice($highlights, 1) as $highlight): ?>
                        <a href="/blog/<?= $highlight['slug'] ?>?blog_id=<?= $highlight['id'] ?>" aria-label="See detail <?= $highlight['title']['rendered'] ?>" class="flex md:flex-row flex-col gap-6 md:w-full min-w-[300px] max-h-[130px] overflow-hidden">
                            <img src="<?= $highlight['_embedded']['wp:featuredmedia'][0]['source_url'] ?>" alt="<?= $highlight['title']['rendered'] ?>" class="bg-cover object-cover md:w-[158px] h-[145px]">
                            <div class="flex flex-col">
                                <h2 class="text-description font-medium text-lg mb-2"><?= $highlight['title']['rendered'] ?></h2>
                                <p class="mb-2 text-xs md:text-sm text-[#9B9B9B]">
                                    <?php
                                    if (isset($highlight['_embedded']['wp:term'][1])) {
                                        $tags = array_map(function ($tag) {
                                            return $tag['name'];
                                        }, $highlight['_embedded']['wp:term'][1]);
                                        echo implode(', ', $tags);
                                    }
                                    ?>
                                    <span>&#8226;</span>
                                    <?= date("d M Y", strtotime($highlight['date'])) ?></p>
                                <p class="text-[#474747] blog-description">
                                    <?= strip_tags($highlight['excerpt']['rendered']) ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="bg-[#F7F7F7] md:px-12 md:py-12 px-6 py-6" x-data="loadMorePosts()">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12" id="blogs-container">
        <?php foreach ($blogs as $blog): ?>
            <a href="/blog/<?= $blog['slug'] ?>?blog_id=<?= $blog['id'] ?>" aria-label="See detail <?= $blog['title']['rendered'] ?>" class="w-full">
            <img src="<?= $blog['_embedded']['wp:featuredmedia'][0]['source_url'] ?>" alt="<?= $blog['title']['rendered'] ?>" class="bg-cover w-full mb-3 h-[150px] md:h-[300px] object-cover">
            <h3 class="md:text-2xl text-lg font-medium mb-3"><?= $blog['title']['rendered'] ?></h3>
            <p class="text-description text-md text-[#474747] mb-3">
                <?= strip_tags($blog['excerpt']['rendered']) ?>
            </p>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="flex justify-center"  x-show="hasMore">
        <button x-show="hasMore" x-on:click="fetchMore()" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer md:w-auto w-full text-center">LOAD MORE</a>
    </div>
</section>
<script>
    function loadMorePosts() {
        return {
            blogs: <?= json_encode($blogs) ?>,
            offset: 11,
            perPage: 6,
            hasMore: <?= count($blogs) >= 6 ? 'true' : 'false' ?>,

            async fetchMore() {
                let url = `http://127.0.0.1:5000/wp-json/wp/v2/posts?_embed=true&lang=<?= $lang ?>&per_page=6&offset=${this.offset}`;

                let response = await fetch(url);
                let newBlogs = await response.json();

                if (newBlogs.length > 0) {
                    this.blogs = [...this.blogs, ...newBlogs];
                    this.offset += this.perPage;
                } else {
                    this.hasMore = false;
                }
            }
        }
    }
</script>