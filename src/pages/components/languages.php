<div x-data="{
        open: false,
        selectedLanguage: 'ENGLISH',
        navbar: <?= isset($navbar) ? $navbar : 'false' ?>,
        get displayLanguage() {
            return this.navbar ? (this.selectedLanguage === 'ENGLISH' ? 'EN' : 'IDN') : this.selectedLanguage;
        }
    }"
     class="inline-block relative">

    <button @click="open = !open"
            :class="{ 'text-[#005FAA]': navbar, 'text-white': !navbar }"
            class="flex items-center gap-1 cursor-pointer focus:outline-none">
        <span class="selected-language" x-text="displayLanguage"></span>
        <svg class="w-4 h-4 transform transition-transform duration-200"
             :class="{ 'rotate-180': open }"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" :class="{ 'top-full': navbar, 'bottom-full': !navbar }"
         class="absolute -left-12 right-2 mt-2 w-32 bg-white text-black rounded-md shadow-lg z-50" x-transition>
        <ul class="py-1">
            <li @click="selectedLanguage = 'ENGLISH'; open = false" class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-gray-800">English</li>
            <li @click="selectedLanguage = 'INDONESIA'; open = false" class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-gray-800">Indonesia</li>
        </ul>
    </div>
</div>
