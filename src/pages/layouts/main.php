<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tests</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
  </head>
  <body>
    <?=$this->section('navbar')?>
    <?=$this->section('content')?>
    <?=$this->section('footer')?>
  </body>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const dropdownButtons = document.querySelectorAll('.dropdown-button');
          const dropdownMenus = document.querySelectorAll('.language-dropdown');
          const selectedLanguageElements = document.querySelectorAll('.selected-language');
          const header = document.getElementById('header');

          window.addEventListener('scroll', function () {
              if (window.scrollY > 50) {
                  header.classList.add('bg-white', 'shadow-md')
              } else {
                  header.classList.remove('bg-white', 'shadow-md')
              }
          })

          dropdownButtons.forEach((button, index) => {
              const dropdownMenu = dropdownMenus[index];
              const selectedLanguageElement = selectedLanguageElements[index];

              button.addEventListener('click', function(e) {
                  e.stopPropagation();
                  dropdownMenu.classList.toggle('hidden');
              });

              document.addEventListener('click', function() {
                  dropdownMenu.classList.add('hidden');
              });

              dropdownMenu.addEventListener('click', function(e) {
                  e.stopPropagation();
              });

              const languageOptions = dropdownMenu.querySelectorAll('li');
              languageOptions.forEach(option => {
                  option.addEventListener('click', function() {
                      const language = this.getAttribute('data-language');
                      selectedLanguageElement.textContent = language.toUpperCase();
                      dropdownMenu.classList.add('hidden');
                  });
              });
          });
      });
  </script>
</html>