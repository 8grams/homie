<?php

/** @var \App\Libs\ViewTemplate $this */

if (isset($_GET['id'])) {
  // do stuff
} else {
  $row = (object) [];
}
?>
<div
  x-init="new Drawer($el, { placement: 'right' }).show()"
  id="drawer-create-blog-default"
  class="fixed top-0 right-0 z-40 w-full h-screen max-w-lg p-4 overflow-y-auto transition-transform bg-white dark:bg-gray-800 translate-x-full"
  tabindex="-1"
  aria-labelledby="drawer-label"
  aria-hidden="true"
  x-data
>
  <h5
    id="drawer-label"
    class="inline-flex items-center mb-6 text-sm font-semibold text-gray-500 uppercase dark:text-gray-400"
  >
    New Blog
  </h5>
  <button
    type="button"
    data-drawer-dismiss="drawer-create-blog-default"
    aria-controls="drawer-create-blog-default"
    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
  >
    <svg
      aria-hidden="true"
      class="w-5 h-5"
      fill="currentColor"
      viewBox="0 0 20 20"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        fill-rule="evenodd"
        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
        clip-rule="evenodd"
      ></path>
    </svg>
    <span class="sr-only">Close menu</span>
  </button>
  <form action="#">
    <div class="space-y-4">
      <div>
        <label
          for="title"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Title
        </label>
        <input
          type="text"
          name="title"
          id="title"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog title"
          required
        />
      </div>
      <div>
        <label
          for="slug"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Slug
        </label>
        <input
          type="text"
          name="slug"
          id="slug"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog slug"
          required
        />
      </div>
      <div>
        <label
          for="hero_image"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Hero Image
        </label>
        <input
          type="text"
          name="hero_image"
          id="hero_image"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog hero image"
          required
        />
      </div>
      <div>
        <label
          for="content"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Content
        </label>
        <textarea x-ref="content" name="content" class="hidden"></textarea>
        <?php include __DIR__ . '/blogformcontent.php' ?>
      </div>
      <div>
        <label
          for="excerpt"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Excerpt
        </label>
        <textarea
          id="excerpt"
          name="excerpt"
          rows="4"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Enter excerpt here"
        ></textarea>
      </div>
      <div>
        <label
          for="header_code_injection"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Header Code Injection
        </label>
        <textarea
          id="header_code_injection"
          name="header_code_injection"
          rows="4"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Enter header code injection here"
        ></textarea>
      </div>
      <div>
        <label
          for="footer_code_injection"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Footer Code Injection
        </label>
        <textarea
          id="footer_code_injection"
          name="footer_code_injection"
          rows="4"
          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Enter footer code injection here"
        ></textarea>
      </div>
      <h6 class="font-bold dark:text-white">Metadata</h6>
      <div>
        <label
          for="meta_title"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Title
        </label>
        <input
          type="text"
          name="meta_title"
          id="meta_title"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog title"
          required
        />
      </div>
      <div>
        <label
          for="meta_description"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Description
        </label>
        <input
          type="text"
          name="meta_description"
          id="meta_description"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog description"
          required
        />
      </div>
      <div>
        <label
          for="meta_keywords"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Keywords
        </label>
        <input
          type="text"
          name="meta_keywords"
          id="meta_keywords"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog keywords"
          required
        />
      </div>
      <div>
        <label
          for="meta_image"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Image
        </label>
        <input
          type="text"
          name="meta_image"
          id="meta_image"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog image"
          required
        />
      </div>
      <div>
        <label
          for="meta_url"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          URL
        </label>
        <input
          type="text"
          name="meta_url"
          id="meta_url"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog url"
          required
        />
      </div>
      <div>
        <label
          for="category_id"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Category
        </label>
        <input
          type="text"
          name="category_id"
          id="category_id"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
          placeholder="Type blog category"
          required
        />
      </div>
      <div>
        <label
          for="tags"
          class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
        >
          Tags
        </label>
        <select
          x-init="
            new Choices($el, {
              choices: [{value: 'Apple', label: 'Apple', selected: false, disabled: false}, {value: 'Banana', label: 'Banana', selected: false, disabled: false}],
              delimiter: ';',
              addChoices: true,
              addItems: true,
              editItems: true,
              removeItems: true,
              removeItemButton: true,
              classNames: {
                containerInner: 'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 p-2.5'.split(' '),
                inputCloned: 'focus:ring-0'
              }
            })
          "
          type="text"
          name="tags"
          id="tags"
          placeholder="Type blog tags"
          multiple
          required
        ></select>
      </div>
      <div class="bottom-0 left-0 flex justify-center w-full pb-4 space-x-4">
        <button
          type="submit"
          class="text-white w-full justify-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
        >
          Add blog
        </button>
        <button
          type="button"
          data-drawer-dismiss="drawer-create-blog-default"
          aria-controls="drawer-create-blog-default"
          class="inline-flex w-full justify-center text-gray-500 items-center bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
        >
          <svg
            aria-hidden="true"
            class="w-5 h-5 -ml-1 sm:mr-1"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            ></path>
          </svg>
          Cancel
        </button>
      </div>
    </div>
  </form>
</div>
