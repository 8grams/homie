<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
/>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<div
  class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700"
>
  <div class="w-full mb-1">
    <div class="mb-4">
      <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol
          class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2"
        >
          <li class="inline-flex items-center">
            <a
              href="/admin/"
              class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white"
            >
              <svg
                class="w-5 h-5 mr-2.5"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                ></path>
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
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                ></path>
              </svg>
              <a
                href="/admin/blogs"
                class="ml-1 text-gray-700 hover:text-primary-600 md:ml-2 dark:text-gray-300 dark:hover:text-white"
              >
                Blogs
              </a>
            </div>
          </li>
        </ol>
      </nav>
      <h1
        class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
      >
        Blogs
      </h1>
    </div>
    <div
      class="items-center justify-between block sm:flex md:divide-x md:divide-gray-100 dark:divide-gray-700"
    >
      <div class="flex items-center mb-4 sm:mb-0">
        <form class="sm:pr-3">
          <label for="products-search" class="sr-only">Search</label>
          <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
            <input
              type="text"
              name="email"
              id="products-search"
              class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              placeholder="Search for blogs"
            />
          </div>
        </form>
      </div>
      <button
        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
        type="button"
        data-drawer-target="drawer-create-blog-default"
        data-drawer-show="drawer-create-blog-default"
        aria-controls="drawer-create-blog-default"
        data-drawer-placement="right"
      >
        Add new blog
      </button>
    </div>
  </div>
</div>

<?php include __DIR__ . '/blogform.php' ?>
