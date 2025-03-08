<?php

$_GET['sqlite'] = '';

function adminer_object() {
    include __DIR__ . "/AdminPlugin.php";

    foreach (glob(__DIR__ . "/plugins/*.php") as $filename) {
        include $filename;
    }
    
    $plugins = array(
        new FCSqliteConnectionWithoutCredentials(),
    );

    return new AdminerPlugin($plugins);
}

include __DIR__ . "/adminer.php";
