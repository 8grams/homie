<header id="header" class="px-8 py-6 fixed w-full top-0 z-10 bg-white shadow-md transition-all duration-300" x-data="{ open: false }">
    <nav aria-label="Main Navigation" class="flex justify-between items-center">
        <a href="/home" class="flex items-center text-[#006AB2] font-bold gap-2">
            <img src="assets/company-logo.png" alt="company" class="h-[56px] w-[48px]">
            ARS SYNO SINERGI
        </a>
        <div class="hidden md:flex gap-8 items-center">
            <ul class="flex gap-8">
                <li class="text-[#005FAA]"><a href="/home">HOME</a></li>
                <li class="text-[#005FAA]"><a href="/about-us">ABOUT US</a></li>
                <li class="text-[#005FAA]">SERVICES</li>
                <li class="text-[#005FAA]">OUR WORKS</li>
                <li class="text-[#005FAA]">BLOGS</li>
            </ul>
            <div class="flex gap-8 items-center">
                <?php $this->insert('components/languages', ['navbar' => json_encode(true)]); ?>
                <a href="/contact-us" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer">CONTACT US</a>
            </div>
        </div>
        <div class="md:hidden flex items-center">
            <button @click="open = !open" class="text-[#006AB2] focus:outline-none cursor-pointer" aria-label="hamburger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>
    <div x-show="open" class="md:hidden flex flex-col items-center gap-6 mt-4 p-6 w-full" x-transition>
        <ul class="flex flex-col gap-6 text-center">
            <li class="text-[#005FAA]"><a href="/home">HOME</a></li>
            <li class="text-[#005FAA]"><a href="/about-us">ABOUT US</a></li>
            <li class="text-[#005FAA]">SERVICES</li>
            <li class="text-[#005FAA]">OUR WORKS</li>
            <li class="text-[#005FAA]">BLOGS</li>
        </ul>
        <?php $this->insert('components/languages', ['navbar' => json_encode(true)]); ?>
        <a href="/contact-us" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer">CONTACT US</a>
    </div>
</header>