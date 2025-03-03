<header id="header" class="px-8 py-6 fixed w-full top-0 z-10 transition-all duration-300">
    <nav aria-label="Main Navigation" class="flex gap-8 justify-between items-center">
        <a href="/home" class="flex items-center text-[#006AB2] font-bold gap-2">
            <img src="assets/company-logo.png" alt="company" class="h-[56px] w-[48px]">
            ARS SYNO SINERGI
        </a>
        <ul class="flex gap-8 hidden md:flex">
            <li class="text-[#005FAA]" aria-label="Home">
                <a href="/home">HOME</a>
            </li>
            <li class="text-[#005FAA]" aria-label="About Us">ABOUT US</li>
            <li class="text-[#005FAA]" aria-label="Services">SERVICES</li>
            <li class="text-[#005FAA]" aria-label="Our Works">OUR WORKS</li>
            <li class="text-[#005FAA]" aria-label="Blogs">BLOGS</li>
        </ul>
        <div class="flex gap-8 hidden md:flex">
            <div class="flex relative">
                <button class="text-white flex items-center gap-1 dropdown-button cursor-pointer">
                    <span class="selected-language text-[#006AB2]">ENGLISH</span>
                    <svg class="w-4 h-4 transform text-[#006AB2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="language-dropdown absolute top-full -left-12 right-2 mt-2 w-32 bg-white text-black rounded-md shadow-lg hidden z-50">
                    <ul class="py-1">
                        <li class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-gray-800" data-language="English">English</li>
                        <li class="px-4 py-2 cursor-pointer hover:bg-gray-100 text-gray-800" data-language="Indonesia">Indonesia</li>
                    </ul>
                </div>
            </div>
            <a href="/contact-us" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer">CONTACT US</a>
        </div>
        <div class="md:hidden flex items-center">
            <button class="text-[#006AB2] focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>
</header>