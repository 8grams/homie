<?php

// accessing helper
// var_dump($this->data["cache"]);

// override 

$user = "Anton";

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
</div>