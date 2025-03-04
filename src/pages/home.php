<?php

// accessing helper
// var_dump($this->data["cache"]);

// override 

$user = "Anton";

// var_dump($this->config);die();

// $rb = $this->db->init();
// $book = $rb->dispense('book');
// $book->author = "glend";
// $id = $rb->store( $book );

// db, cache, curl/http client

?>

<h1>Home</h1>
Hello world <?= $user ?>!

<?php $this->start('navbar') ?>
    <h1>Override Navbar</h1>
    <p>New Navbar</p>
<?php $this->stop() ?>

<div>
<?php $this->loadComponent('drawer') ?>

<?= $this->trans('home', 'Rumah') ?>
</div>

<img src="/banner.jpg" data-asset="contoh-asset-multiple">
<img src="/banner.jpg" data-asset="contoh-asset-multiple">
<img src="/banner.jpg" data-asset="contoh-asset-multiple">

<div data-trans="contoh-trans-multiple">Hello world!</div>
<div data-trans="contoh-trans-multiple">Hello world!</div>
<div data-trans="contoh-trans-multiple">Hello world!</div>

<div data-trans="home"><?= $this->trans('home', 'Rumah') ?></div>