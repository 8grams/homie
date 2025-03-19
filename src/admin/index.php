<?php

/** @var \App\Libs\ViewTemplate $this */
if ($this->request->getMethod() == 'POST') {
  $payload = $this->request->getPayload();
  $lang = $payload->get('lang');
  $trans = $payload->all('trans');
  /** @var \Symfony\Component\HttpFoundation\File\UploadedFile[] */
  $asset = $this->request->files->all('asset');
  $pdo = $this->db->getPDO();
  $statement = $pdo->prepare("
    INSERT INTO translations (locale, label, value)
    VALUES (:locale, :label, :value)
    ON CONFLICT (locale, label) DO UPDATE
    SET value = :value
    WHERE locale = :locale AND label = :label
  ");
  $pdo->beginTransaction();
  foreach ($trans as $key => $value) {
    $statement->execute([
      'locale' => $lang,
      'label' => $key,
      'value' => $value,
    ]);
  }
  $getAsset = $pdo->prepare("SELECT * FROM assets WHERE key = ?");
  $setAsset = $pdo->prepare("
    INSERT INTO assets (key, src)
    VALUES (:key, :src)
    ON CONFLICT (key) DO UPDATE
    SET src = :src
    WHERE key = :key
  ");
  foreach ($asset as $key => $file) {
    if (!$file) {
      continue;
    }
    $ext = $file->getClientOriginalExtension();
    $src = '/data/assets/' . $key . '.' . $ext;
    $getAsset->execute([$key]);
    $row = $getAsset->fetch();
    if ($row) {
      @unlink('..' . $row['src']);
    }
    $file->move('../data/assets', $key . '.' . $ext);
    $setAsset->execute([
      'key' => $key,
      'src' => $src,
    ]);
  }
  $pdo->commit();
  header('Location: ' . $this->request->getUri());
  exit;
}
?>
<div class="grow flex flex-col lg:flex-row h-full" x-data="contentEditor">
  <iframe class="grow bg-white dark:bg-gray-800" x-ref="iframe" :src="`/${selectedLang}/`" @load="load"></iframe>
  <form class="w-80 p-4 flex flex-col gap-4 overflow-y-auto bg-white dark:bg-gray-800" method="post" enctype="multipart/form-data">
    <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 dark:text-gray-400 self-end">
      <template x-for="lang in langs">
        <li class="me-2">
          <a
            class="inline-block px-4 py-3 rounded-lg cursor-pointer"
            :class="lang === selectedLang ? 'text-white bg-blue-600 active' : 'hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-white'"
            @click="selectedLang = lang"
            x-text="lang"></a>
        </li>
      </template>
    </ul>
    <input type="hidden" name="lang" :value="selectedLang">
    <ul>
      <template x-for="entry in entries">
        <li>
          <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" x-text="entry.key">
            Name
          </label>
          <template x-if="entry.type === 'trans'">
            <textarea
              :name="`trans[${entry.key}]`"
              type="text"
              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 mb-5"
              :value="entry.value"
              @focus="focus"
              @blur="blur"
              @input="input"></textarea>
          </template>
          <template x-if="entry.type === 'asset'">
            <label>
              <img :src="entry.value" />
              <input type="file" :name="`asset[${entry.key}]`" @input="fileInput" hidden />
            </label>
          </template>
        </li>
      </template>
    </ul>
    <button
      class="w-full justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
      Save
    </button>
  </form>
</div>