<?php

$hello = 'admin';

?>


Hello <?= $hello ?>!

<script type="module" src="/content-editor.js"></script>

<div class="flex min-h-100" x-data="contentEditor">
  <iframe class="grow" x-ref="iframe" src="/home" @load="load"></iframe>
  <div class="w-80 p-4 flex flex-col gap-4 bg-base-200">
    <ul>
      <template x-for="entry in entries">
        <li>
          <fieldset class="fieldset">
            <legend class="fieldset-legend" x-text="entry.key"></legend>
            <template x-if="entry.type === 'trans'">
              <textarea
                class="textarea resize-none"
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
          </fieldset>
        </li>
      </template>
    </ul>
    <button class="btn btn-primary" @click="save">Save</button>
    <template x-if="lines.length">
      <div class="mockup-code">
        <template x-for="line in lines">
          <pre :data-prefix="line.number"><code x-text="line.text"></code></pre>
        </template>
      </div>
    </template>
  </div>
</div>