<div class="flex flex-col lg:flex-row h-full" x-data="contentEditor">
  <iframe class="grow bg-white dark:bg-gray-800" x-ref="iframe" src="/home" @load="load"></iframe>
  <div class="w-80 p-4 flex flex-col gap-4 overflow-y-auto bg-white dark:bg-gray-800">
    <ul>
      <template x-for="entry in entries">
        <li>
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" x-text="entry.key">
            Name
          </label>
          <template x-if="entry.type === 'trans'">
            <textarea
              type="text"
              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              :value="entry.value"
              @focus="focus"
              @blur="blur"
              @input="input"></textarea>
          </template>
          <template x-if="entry.type === 'asset'">
            <label>
              <img :src="entry.value" />
              <input type="file" @input="fileInput" hidden />
            </label>
          </template>
        </li>
      </template>
    </ul>
    <button
      class="w-full justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
      @click="save">
      Save
    </button>
    <template x-if="lines.length">
      <div class="overflow-auto">
        <template x-for="line in lines">
          <pre :data-prefix="line.number"><code x-text="line.text"></code></pre>
        </template>
      </div>
    </template>
  </div>
</div>