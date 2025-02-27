<?php

// accessing helper
// var_dump($this->data["cache"]);

// override 

$user = "Anton";

?>

<h1>Home</h1>
Hello world <?= $user ?>!

<?php $this->start('navbar') ?>
    <h1>Override Navbar</h1>
    <p>New Navbar</p>
<?php $this->stop() ?>

<?php $this->loadComponent('drawer') ?>