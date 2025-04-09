<?php

/** @var \App\Libs\ViewTemplate $this */

$error = null;
$pdo = $this->db->getPDO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (empty($_POST['category'])) {
    $category = null;
  } else {
    $statement = $pdo->prepare('INSERT INTO categories (category) VALUES (?) ON CONFLICT (category) DO UPDATE SET category = excluded.category RETURNING id');
    $statement->execute([$_POST['category']]);
    $category = $statement->fetchColumn();
  }
  if (empty($_POST['tags'])) {
    $tags = [];
  } else {
    $tags = explode(';', $_POST['tags']);
    $statement = $pdo->prepare('INSERT INTO tags (tag) VALUES '
      . implode(', ', array_map(fn() => '(?)', $tags))
      . ' ON CONFLICT (tag) DO UPDATE SET tag = excluded.tag RETURNING id');
    $statement->execute($tags);
    $tags = array_map(fn($row) => $row['id'], $statement->fetchAll());
  }
  $id = $_GET['id'] ?? null;
  if (!$id) {
    $sql = 'INSERT INTO blogs (title, content, slug, excerpt, header_code_injection, footer_code_injection, meta_title, meta_description, meta_keywords, meta_url, category_id, created_at, updated_at) VALUES (:title, :content, :slug, :excerpt, :header_code_injection, :footer_code_injection, :meta_title, :meta_description, :meta_keywords, :meta_url, :category_id, :created_at, :updated_at)';
    $args = array_merge(
      array_diff_key($_POST, ['category' => null, 'tags' => null]),
      [
        'category_id' => $category,
        'updated_at' => date('c'),
        'created_at' => date('c'),
      ]
    );
  } else {
    $sql = 'UPDATE blogs SET title = :title, content = :content, slug = :slug, excerpt = :excerpt, header_code_injection = :header_code_injection, footer_code_injection = :footer_code_injection, meta_title = :meta_title, meta_description = :meta_description, meta_keywords = :meta_keywords, meta_url = :meta_url, category_id = :category_id, updated_at = :updated_at WHERE id = :id';
    $args = array_merge(
      array_diff_key($_POST, ['category' => null, 'tags' => null]),
      [
        'category_id' => $category,
        'updated_at' => date('c'),
        'id' => $_GET['id'],
      ]
    );
  }
  try {
    $statement = $pdo->prepare($sql);
    $statement->execute($args);
    if (!$id) {
      $id = $pdo->lastInsertId();
    }
    $statement = $pdo->prepare('DELETE FROM tags_blogs WHERE blog_id = ?');
    $statement->execute([$id]);
    $statement = $pdo->prepare('INSERT INTO tags_blogs (blog_id, tag_id) VALUES (?, ?)');
    foreach ($tags as $tag) {
      $statement->execute([$id, $tag]);
    }
    /** @var ?\Symfony\Component\HttpFoundation\File\UploadedFile */
    $hero_image = $this->request->files->get('hero_image');
    if ($hero_image) {
      // ../data/assets/<id>_hero_image.<ext>
      $ext = $hero_image->getClientOriginalExtension();
      $hero_image->move('../data/assets', $id . '_hero_image.' . $ext);
      $statement = $pdo->prepare('UPDATE blogs SET hero_image = ? WHERE id = ?');
      $statement->execute(['/data/assets/' . $id . '_hero_image.' . $ext, $id]);
    }
    /** @var ?\Symfony\Component\HttpFoundation\File\UploadedFile */
    $meta_image = $this->request->files->get('meta_image');
    if ($meta_image) {
      // ../data/assets/<id>_meta_image.<ext>
      $ext = $meta_image->getClientOriginalExtension();
      $meta_image->move('../data/assets', $id . '_meta_image.' . $ext);
      $statement = $pdo->prepare('UPDATE blogs SET meta_image = ? WHERE id = ?');
      $statement->execute(['/data/assets/' . $id . '_meta_image.' . $ext, $id]);
    }
    header('Location: /admin/blogs');
    exit;
  } catch (\PDOException $ex) {
    if (strpos($ex->getMessage(), 'slug') !== false) {
      $error = 'Slug must be unique.';
    } else {
      $error = 'Unknown error.';
    }
  }
}

$categories = array_map(
  fn($row) => $row['category'],
  $pdo->query('SELECT category FROM categories')->fetchAll()
);

$tags = array_map(
  fn($row) => $row['tag'],
  $pdo->query('SELECT tag FROM tags')->fetchAll()
);

if (isset($_GET['id'])) {
  $statement = $pdo->prepare('SELECT * FROM blogs WHERE id = ?');
  $statement->execute([$_GET['id']]);
  $row = $statement->fetchObject();
  $statement = $pdo->prepare('SELECT category FROM categories WHERE id = ?');
  $statement->execute([$row->category_id]);
  $row->category = $statement->fetchAll(PDO::FETCH_COLUMN);
  $statement = $pdo->prepare('SELECT tag FROM tags_blogs JOIN tags ON tags_blogs.tag_id = tags.id  WHERE blog_id = ? ');
  $statement->execute([$row->id]);
  $row->tags = $statement->fetchAll(PDO::FETCH_COLUMN);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $row = (object) $_POST;
} else {
  $row = (object) [];
  $row->category = [];
  $row->tags = [];
}

if (empty($_GET['id'])) {
  $title = 'New Blog';
} else {
  $title = 'Edit Blog';
}
?>

<div class="w-full mb-1 p-4 bg-white block border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
  <div class="mb-4">
    <nav class="flex mb-5" aria-label="Breadcrumb">
      <ol
        class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
        <li class="inline-flex items-center">
          <a
            href="/admin/"
            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
            <svg
              class="w-5 h-5 mr-2.5"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            Home
          </a>
        </li>
        <li>
          <div class="flex items-center">
            <svg
              class="w-6 h-6 text-gray-400"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
            </svg>
            <a
              href="/admin/blogs"
              class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
              Blogs
            </a>
          </div>
        </li>
        <li>
          <div class="flex items-center">
            <svg
              class="w-6 h-6 text-gray-400"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
            </svg>
            <a
              href="/admin/blogs_form"
              class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
              <?= $title ?>
            </a>
          </div>
        </li>
      </ol>
    </nav>
    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
      <?= $title ?>
    </h1>
  </div>
  <?php if ($error): ?>
    <div
      class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
      role="alert">
      <span class="font-medium"><?= htmlspecialchars($error) ?></span>
    </div>
  <?php endif ?>
  <form method="post" enctype="multipart/form-data" x-data>
    <div class="space-y-4">
      <div>
        <label
          for="title"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Title
        </label>
        <input
          type="text"
          name="title"
          id="title"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog title"
          value="<?= htmlspecialchars($row->title ?? '') ?>" />
      </div>
      <div>
        <label
          for="slug"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Slug
        </label>
        <input
          type="text"
          name="slug"
          id="slug"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog slug"
          value="<?= htmlspecialchars($row->slug ?? '') ?>" />
      </div>
      <div>
        <label
          for="hero_image"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Hero Image
        </label>
        <div
          data-file="<?= $row->hero_image ?? '' ?>"
          class="w-full h-64 overflow-hidden cursor-pointer border border-gray-300 text-gray-300 rounded-lg dark:border-gray-600 dark:text-gray-600"
          x-data="{ file: $el.dataset.file }"
          @click="$refs.input.click()">
          <input
            x-ref="input"
            @input="file = URL.createObjectURL($el.files[0])"
            class="hidden"
            type="file"
            name="hero_image" />
          <template x-if="!file">
            <div class="p-4 h-full flex justify-center items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="48"
                height="48"
                viewBox="0 0 24 24">
                <path
                  fill="currentColor"
                  d="m16 21l-4.762-8.73L15 6l8 15zM8 10l6 11H2zM5.5 8a2.5 2.5 0 1 1 0-5a2.5 2.5 0 0 1 0 5" />
              </svg>
            </div>
          </template>
          <template x-if="file">
            <img
              class="w-full h-full object-cover"
              :src="file" />
          </template>
        </div>
      </div>
      <div>
        <label
          for="content"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Content
        </label>
        <textarea x-ref="content" name="content" class="hidden"><?= htmlspecialchars($row->content ?? '') ?></textarea>
        <?php include __DIR__ . '/blogs_form_content.php' ?>
      </div>
      <div>
        <label
          for="excerpt"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Excerpt
        </label>
        <textarea
          id="excerpt"
          name="excerpt"
          rows="4"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Enter excerpt here"><?= htmlspecialchars($row->excerpt ?? '') ?></textarea>
      </div>
      <div>
        <label
          for="header_code_injection"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Header Code Injection
        </label>
        <textarea
          id="header_code_injection"
          name="header_code_injection"
          rows="4"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Enter header code injection here"><?= htmlspecialchars($row->header_code_injection ?? '') ?></textarea>
      </div>
      <div>
        <label
          for="footer_code_injection"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Footer Code Injection
        </label>
        <textarea
          id="footer_code_injection"
          name="footer_code_injection"
          rows="4"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Enter footer code injection here"><?= htmlspecialchars($row->footer_code_injection ?? '') ?></textarea>
      </div>
      <h6 class="font-bold dark:text-white">Metadata</h6>
      <div>
        <label
          for="meta_title"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Title
        </label>
        <input
          type="text"
          name="meta_title"
          id="meta_title"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog title"
          value="<?= htmlspecialchars($row->meta_title ?? '') ?>" />
      </div>
      <div>
        <label
          for="meta_description"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Description
        </label>
        <input
          type="text"
          name="meta_description"
          id="meta_description"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog description"
          value="<?= htmlspecialchars($row->meta_description ?? '') ?>" />
      </div>
      <div>
        <label
          for="meta_keywords"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Keywords
        </label>
        <input
          type="text"
          name="meta_keywords"
          id="meta_keywords"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog keywords"
          value="<?= htmlspecialchars($row->meta_keywords ?? '') ?>" />
      </div>
      <div>
        <label
          for="meta_image"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Image
        </label>
        <div
          data-file="<?= $row->meta_image ?? '' ?>"
          class="w-full h-64 overflow-hidden cursor-pointer border border-gray-300 text-gray-300 rounded-lg dark:border-gray-600 dark:text-gray-600"
          x-data="{ file: $el.dataset.file }"
          @click="$refs.input.click()">
          <input
            x-ref="input"
            @input="file = URL.createObjectURL($el.files[0])"
            class="hidden"
            type="file"
            name="meta_image" />
          <template x-if="!file">
            <div class="p-4 h-full flex justify-center items-center">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="48"
                height="48"
                viewBox="0 0 24 24">
                <path
                  fill="currentColor"
                  d="m16 21l-4.762-8.73L15 6l8 15zM8 10l6 11H2zM5.5 8a2.5 2.5 0 1 1 0-5a2.5 2.5 0 0 1 0 5" />
              </svg>
            </div>
          </template>
          <template x-if="file">
            <img
              class="w-full h-full object-cover"
              :src="file" />
          </template>
        </div>
      </div>
      <div>
        <label
          for="meta_url"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          URL
        </label>
        <input
          type="text"
          name="meta_url"
          id="meta_url"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog url"
          value="<?= htmlspecialchars($row->meta_url ?? '') ?>" />
      </div>
      <div x-data>
        <label
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Category
        </label>
        <div
          data-name="category"
          data-options="<?= htmlspecialchars(implode(';', $categories)) ?>"
          data-selected="<?= htmlspecialchars(implode(';', $row->category)) ?>"
          data-max="1"
          class="bg-gray-50 border border-gray-300 rounded relative p-1 flex gap-1 items-center text-sm"
          x-data="{
            name: $el.dataset.name,
            options: $el.dataset.options.split(';'),
            selected: $el.dataset.selected.split(';').filter(x => !!x),
            max: parseInt($el.dataset.max),
            value: '',
            show: false,
            selectedIndex: 0,
            filtered () {
              return this.options
                .filter(option => {
                  return !this.selected.includes(option)
                })
                .filter(option => {
                  return option.toLowerCase().includes(this.value.toLowerCase())
                })
            }
          }">
          <input type="hidden" x-bind:name="name" x-bind:value="selected.join(';')">
          <template x-for="option in selected">
            <div class="bg-primary-700 text-white py-1 px-2 rounded" x-text="option"></div>
          </template>
          <div class="relative grow">
            <input
              class="bg-transparent outline-none w-full py-1 px-1"
              x-model="value"
              x-on:focus="show = true"
              x-on:blur="show = false"
              x-on:keydown="show = true"
              x-on:keydown.up="selectedIndex = Math.max(0, selectedIndex - 1)"
              x-on:keydown.down="selectedIndex = Math.min(filtered().length - 1, selectedIndex + 1)"
              x-on:keydown.enter.prevent="
                if (max === -1 || selected.length < max) {
                  if (filtered().length) {
                    selected.push(filtered()[selectedIndex])
                    selectedIndex = Math.max(0, Math.min(filtered().length - 1, selectedIndex))
                  } else if (!selected.includes(value)) {
                    selected.push(value)
                  }
                }
                value = ''
                show = false
              "
              x-on:keydown.backspace="
                if (value === '') {
                  selected.pop()
                  selectedIndex = Math.max(0, Math.min(filtered().length - 1, selectedIndex))
                  show = false
                }
              " />
            <div
              class="absolute rounded shadow border border-gray-100 overflow-hidden bg-white cursor-default z-10"
              x-show="show">
              <template x-for="(option, index) in filtered()">
                <div
                  class="p-2 hover:bg-sky-100"
                  x-bind:class="selectedIndex === index ? 'bg-primary-700 hover:bg-sky-600 text-white' : ''"
                  x-text="option"
                  x-on:mousedown="
                    if (max === -1 || selected.length < max) {
                      selected.push(option)
                    }
                  "></div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div x-data>
        <label
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Tags
        </label>
        <div
          data-name="tags"
          data-options="<?= htmlspecialchars(implode(';', $tags)) ?>"
          data-selected="<?= htmlspecialchars(implode(';', $row->tags)) ?>"
          data-max="-1"
          class="bg-gray-50 border border-gray-300 rounded relative p-1 flex gap-1 items-center text-sm"
          x-data="{
            name: $el.dataset.name,
            options: $el.dataset.options.split(';'),
            selected: $el.dataset.selected.split(';').filter(x => !!x),
            max: parseInt($el.dataset.max),
            value: '',
            show: false,
            selectedIndex: 0,
            filtered () {
              return this.options
                .filter(option => {
                  return !this.selected.includes(option)
                })
                .filter(option => {
                  return option.toLowerCase().includes(this.value.toLowerCase())
                })
            }
          }">
          <input type="hidden" x-bind:name="name" x-bind:value="selected.join(';')">
          <template x-for="option in selected">
            <div class="bg-primary-700 text-white py-1 px-2 rounded" x-text="option"></div>
          </template>
          <div class="relative grow">
            <input
              class="bg-transparent outline-none w-full py-1 px-1"
              x-model="value"
              x-on:focus="show = true"
              x-on:blur="show = false"
              x-on:keydown="show = true"
              x-on:keydown.up="selectedIndex = Math.max(0, selectedIndex - 1)"
              x-on:keydown.down="selectedIndex = Math.min(filtered().length - 1, selectedIndex + 1)"
              x-on:keydown.enter.prevent="
                if (max === -1 || selected.length < max) {
                  if (filtered().length) {
                    selected.push(filtered()[selectedIndex])
                    selectedIndex = Math.max(0, Math.min(filtered().length - 1, selectedIndex))
                  } else if (!selected.includes(value)) {
                    selected.push(value)
                  }
                }
                value = ''
                show = false
              "
              x-on:keydown.backspace="
                if (value === '') {
                  selected.pop()
                  selectedIndex = Math.max(0, Math.min(filtered().length - 1, selectedIndex))
                  show = false
                }
              " />
            <div
              class="absolute rounded shadow border border-gray-100 overflow-hidden bg-white cursor-default z-10"
              x-show="show">
              <template x-for="(option, index) in filtered()">
                <div
                  class="p-2 hover:bg-sky-100"
                  x-bind:class="selectedIndex === index ? 'bg-primary-700 hover:bg-sky-600 text-white' : ''"
                  x-text="option"
                  x-on:mousedown="
                    if (max === -1 || selected.length < max) {
                      selected.push(option)
                    }
                  "></div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div class="flex pb-4 space-x-4">
        <button
          type="submit"
          class="text-white justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
          Save blog
        </button>
        <a
          href="/admin/blogs"
          class="inline-flex justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
          <svg
            aria-hidden="true"
            class="w-5 h-5 -ml-1 sm:mr-1"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          Cancel
        </a>
      </div>
    </div>
  </form>
</div>