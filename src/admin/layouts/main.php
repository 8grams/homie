<!DOCTYPE html>
<html class="h-full">

<head>
  <title>Tests</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/app.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script type="module" src="/content-editor.js"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-800 h-full">
  <?= $this->section('navbar') ?>
  <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900 h-full">
    <?= $this->insert('layouts/sidebar') ?>
    <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900 h-full">
      <main class="h-full">
        <?= $this->section('content') ?>
      </main>
    </div>
  </div>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="/app.bundle.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.2/datepicker.min.js"></script>
</body>

</html>