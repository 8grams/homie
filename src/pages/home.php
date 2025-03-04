<?php $this->start('components/tag') ?>
    <title>Home</title>
<?php $this->stop() ?>
<section class="relative bg-cover bg-center bg-no-repeat p-0 md:p-12 h-[60vh] md:h-[90vh] w-full">
    <div class="absolute inset-0 bg-[url('assets/bg-wrapper.jpeg')] bg-cover bg-center bg-no-repeat opacity-50"></div>
    <div class="relative flex items-center justify-center h-full w-full px-4">
        <div class="md:text-center text-inherit max-w-xl w-full">
            <h2 class="text-[#006AB2] font-semibold text-2xl md:text-[50px]">
                Turning Challenges Into Opportunities with Expert Consulting
            </h2>
            <p class="mt-2 md:mt-4 text-base md:text-xl text-[#474747]">
                We help businesses and government agencies navigate complexity, drive innovation, and achieve sustainable growth.
            </p>
            <button class="bg-[#006AB2] px-4 py-2 text-white font-medium cursor-pointer mt-4 md:mt-6 w-full md:w-auto">SEE OUR SERVICES</button>
        </div>
    </div>
</section>
<section class="md:my-12 mt-6">
    <div class="md:px-8 md:py-6 px-4 py-4 w-full flex flex-col md:flex-row gap-8 justify-between">
        <div class="max-w-3xl w-full leading-[44px]">
            <h3 class="font-semibold text-[#FF8A15] mb-3">ABOUT US</h3>
            <p class="mb-5 text-xl md:text-2xl">At Ars Syno Sinergi, we turn challenges into success with the right strategy. Our team of expert consultants specializes in telecommunications,
                business investment, infrastructure, and environmental compliance.</p>
            <p class="mb-5 text-xl md:text-2xl">With years of experience working with government and private sectors,
                we provide practical solutions to complex problems. Whether you need support for a large-scale project or regulatory guidance, we’re the
                partner you can trust and rely on. </p>
            <a href="/about-us" class="text-[#006AB2] flex">
                LEARN MORE
                <img src="assets/arrow-right.svg" alt="arrow-right">
            </a>
        </div>
        <img src="assets/bg-about-us.jpeg" alt="about us" class="w-[480px] h-[500px] md:h-[700px] bg-cover">
    </div>
</section>
<?php $this->insert('components/our-services'); ?>
<?php $this->insert('components/why-choose-us'); ?>
<section class="md:py-12 md:px-12 py-6 px-6 bg-white">
    <div class="flex md:flex-row flex-col justify-between items-end md:mb-10 mb-6">
        <div class="text-center md:text-left w-full md:w-auto">
            <h3 class="font-semibold text-[#FF8A15] mb-3">FEATURED PROJECTS</h3>
            <h2 class="text-[#002644] font-semibold text-2xl md:text-[50px]">Real Results. <br class="md:hidden block"/> Meaningful Impact</h2>
        </div>
        <a href="/contact-us" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer md:block hidden">VIEW ALL</a>
    </div>
    <div class="bg-[#FBFBFB] py-5 px-5 mb-5">
       <div class="flex flex-col md:flex-row items-center justify-between md:gap-12 gap-6">
           <img src="assets/project-placeholder.png" alt="placeholder image" class="bg-cover">
           <div class="flex flex-col md:flex-row justify-between">
               <div class="flex flex-col justify-between w-full md:w-1/3 md:space-y-12 space-y-4">
                   <h3 class="md:text-2xl text-lg font-medium">Bappenas - Infrastructure & PPP Projects</h3>
                   <p class="text-[#474747] w-full text-sm overflow-hidden text-ellipsis
    [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical] max-h-10 md:hidden block">
                       Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                       human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                       telecommunications and information accessibility across Indonesia.
                   </p>
                   <a href="/contact-us" class="border-[#006AB2] border-1 text-[#006AB2] px-4 py-2 cursor-pointer w-full md:max-w-[155px] flex md:justify-between justify-center items-center gap-2">
                      SEE DETAIL
                       <img src="assets/arrow-right.svg" alt="arrow-right">
                   </a>
               </div>
               <p class="text-[#474747] w-1/3 text-sm overflow-hidden text-ellipsis
    [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical] max-h-10 md:block hidden">
                   Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                   human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                   telecommunications and information accessibility across Indonesia.
               </p>
           </div>
       </div>
    </div>
    <div class="bg-[#FBFBFB] py-5 px-5 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between md:gap-12 gap-6">
            <img src="assets/project-placeholder.png" alt="placeholder image" class="bg-cover">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="flex flex-col justify-between w-full md:w-1/3 md:space-y-12 space-y-4">
                    <h3 class="md:text-2xl text-lg font-medium">Bappenas - Infrastructure & PPP Projects</h3>
                    <p class="text-[#474747] w-full text-sm overflow-hidden text-ellipsis
    [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical] max-h-10 md:hidden block">
                        Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                        human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                        telecommunications and information accessibility across Indonesia.
                    </p>
                    <a href="/contact-us" class="border-[#006AB2] border-1 text-[#006AB2] px-4 py-2 cursor-pointer w-full md:max-w-[155px] flex md:justify-between justify-center items-center gap-2">
                        SEE DETAIL
                        <img src="assets/arrow-right.svg" alt="arrow-right">
                    </a>
                </div>
                <p class="text-[#474747] w-1/3 text-sm overflow-hidden text-ellipsis
    [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical] max-h-10 md:block hidden">
                    Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                    human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                    telecommunications and information accessibility across Indonesia.
                </p>
            </div>
        </div>
    </div>
    <div class="bg-[#FBFBFB] py-5 px-5 mb-5">
        <div class="flex flex-col md:flex-row items-center justify-between md:gap-12 gap-6">
            <img src="assets/project-placeholder.png" alt="placeholder image" class="bg-cover">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="flex flex-col justify-between w-full md:w-1/3 md:space-y-12 space-y-4">
                    <h3 class="md:text-2xl text-lg font-medium">Bappenas - Infrastructure & PPP Projects</h3>
                    <p class="text-[#474747] w-full text-sm overflow-hidden text-ellipsis
    [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical] max-h-10 md:hidden block">
                        Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                        human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                        telecommunications and information accessibility across Indonesia.
                    </p>
                    <a href="/contact-us" class="border-[#006AB2] border-1 text-[#006AB2] px-4 py-2 cursor-pointer w-full md:max-w-[155px] flex md:justify-between justify-center items-center gap-2">
                        SEE DETAIL
                        <img src="assets/arrow-right.svg" alt="arrow-right">
                    </a>
                </div>
                <p class="text-[#474747] w-1/3 text-sm overflow-hidden text-ellipsis
    [display:-webkit-box] [-webkit-line-clamp:2] [-webkit-box-orient:vertical] max-h-10 md:block hidden">
                    Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                    human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                    telecommunications and information accessibility across Indonesia.
                </p>
            </div>
        </div>
    </div>
</section>
<section class="md:py-12 md:px-12 py-6 px-6 bg-[#F7F7F7]">
    <div class="flex md:flex-row flex-col justify-between items-end md:mb-10 mb-6">
        <div class="text-center md:text-left w-full md:w-auto">
            <h3 class="font-semibold text-[#FF8A15] mb-3">BLOGS</h3>
            <h2 class="text-[#002644] font-semibold text-2xl md:text-[50px]">Stay Ahead with Expert Insights</h2>
        </div>
        <a href="/blogs" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer md:block hidden">VIEW ALL</a>
    </div>
    <div class="overflow-x-auto mb-6">
        <div class="flex gap-4 min-w-max sm:w-full lg:w-full justify-between">
            <div class="w-[350px] sm:w-[45vw] md:w-[30vw] lg:w-[450px] shrink-0">
                <img src="assets/project-placeholder.png" alt="blogs placeholder #1" class="w-full mb-2">
                <h3 class="text-xl md:text-2xl mb-2">Indonesia’s Bold Move to Relocate Its Capital</h3>
                <p class="mb-2 text-xs md:text-sm text-[#9B9B9B]">GOVERNANCE <span>&#8226;</span> 13 FEB 2025</p>
                <p class="text-xs md:text-sm text-[#474747] line-clamp-2">
                    Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                    human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                    telecommunications and information accessibility across Indonesia.
                </p>
            </div>
            <div class="w-[350px] sm:w-[45vw] md:w-[30vw] lg:w-[450px] shrink-0">
                <img src="assets/project-placeholder.png" alt="blogs placeholder #2" class="w-full mb-2">
                <h3 class="text-xl md:text-2xl mb-2">Indonesia’s Bold Move to Relocate Its Capital</h3>
                <p class="mb-2 text-xs md:text-sm text-[#9B9B9B]">GOVERNANCE <span>&#8226;</span> 13 FEB 2025</p>
                <p class="text-xs md:text-sm text-[#474747] line-clamp-2">
                    Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                    human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                    telecommunications and information accessibility across Indonesia.
                </p>
            </div>
            <div class="w-[350px] sm:w-[45vw] md:w-[30vw] lg:w-[450px] shrink-0">
                <img src="assets/project-placeholder.png" alt="blogs placeholder #3" class="w-full mb-2">
                <h3 class="text-xl md:text-2xl mb-2">Indonesia’s Bold Move to Relocate Its Capital</h3>
                <p class="mb-2 text-xs md:text-sm text-[#9B9B9B]">GOVERNANCE <span>&#8226;</span> 13 FEB 2025</p>
                <p class="text-xs md:text-sm text-[#474747] line-clamp-2">
                    Over the past few years, we have consistently and sustainably provided consulting services and high-quality
                    human resources to BAKTI (Badan Aksesibilitas Telekomunikasi dan Informasi), supporting their mission to enhance
                    telecommunications and information accessibility across Indonesia.
                </p>
            </div>
        </div>
    </div>
    <a href="/blogs" class="bg-[#006AB2] px-4 py-2 text-white cursor-pointer md:hidden block text-center">VIEW ALL</a>
</section>
<?php $this->insert('components/client-say'); ?>






