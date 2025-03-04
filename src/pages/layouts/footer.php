<footer>
    <div class="bg-cover bg-center bg-no-repeat p-0 md:p-12 w-full h-[60vh]" style="background-image: url('assets/bg-wrapper.jpeg')">
        <div class="flex items-center flex-col justify-center h-full w-full px-4">
            <div class="md:text-center text-inherit max-w-xl w-full">
                <h3 class="font-semibold text-[#FF8A15] mb-3">LET'S BUILD SOMETHING GREAT</h3>
                <h2 class="text-[#006AB2] font-normal text-2xl md:text-[50px] mb-5">Need expert consulting to
                    take your project further?</h2>
                <p class="mb-5 font-normal text-[#474747]">
                    We specialize in strategic solutions that drive real results. Whether you're launching a new initiative, expanding operations, or navigating complex regulations, we're here to help.
                </p>
                <p class="font-semibold text-[#585858]">Get in touch with us today.
                    <br/>
                    Let's build something great together.</p>
                <button class="bg-[#FF8A15] px-4 py-2 text-white font-medium cursor-pointer mt-4 md:mt-6 w-full md:w-auto">LET'S TALK</button>
            </div>
        </div>
    </div>
    <div class="px-8 py-6 bg-[#005FAA] relative">
        <div class="flex md:flex-row flex-col justify-between items-center gap-5 mb-8">
            <a href="/home" class="flex items-center text-white font-bold gap-2">
                <img src="assets/company-logo.png" alt="company" class="h-[56px] w-[48px]">
                ARS SYNO SINERGI
            </a>
            <ul class="flex md:flex-row flex-col items-center justify-center gap-5">
                <li class="text-white" aria-label="Home">HOME</li>
                <li class="text-white" aria-label="About Us">ABOUT US</li>
                <li class="text-white" aria-label="Services">SERVICES</li>
                <li class="text-white" aria-label="Our Works">OUR WORKS</li>
                <li class="text-white" aria-label="Blogs">BLOGS</li>
                <li class="text-white" aria-label="Contact">CONTACT</li>
            </ul>
            <div class="flex flex-row items-center justify-center gap-5">
                <a href="" class="cursor-pointer">
                    <img src="assets/icons/linkedin.svg" alt="linkedin icon">
                </a>
                <a href="" class="cursor-pointer">
                    <img src="assets/icons/instagram.svg" alt="instagram icon">
                </a>
            </div>
        </div>
        <hr class="border-[#ABCEED] mb-8">
        <div class="flex md:flex-row flex-col justify-between items-center gap-5 text-white">
            <p class="order-2 md:order-1">COPYRIGHT © 2025  Ars Syno Sinergi</p>
            <ul class="flex items-center md:flex-row flex-col justify-center gap-5 order-1 md:order-2">
                <li class="text-white" aria-label="Home">PRIVACY POLICY</li>
                <li class="text-white" aria-label="About Us">TERMS OF SERVICES</li>
                <li class="text-white relative">
                    <?php $this->insert('components/languages'); ?>
                </li>
            </ul>
        </div>
    </div>
</footer>
