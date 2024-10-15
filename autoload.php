<?php

spl_autoload_register(function ($name) {
    if (file_exists(__DIR__ . '/app/Controllers/' . $name . '.php')) {
        require __DIR__ . '/app/Controllers/' . $name . '.php';
    }
    elseif (file_exists(__DIR__ . '/app/Models/' . $name . '.php')) {
        require __DIR__ . '/app/Models/' . $name . '.php';
    }
    elseif (file_exists(__DIR__ . '/app/Core/' . $name . '.php')) {
        require __DIR__ . '/app/Core/' . $name . '.php';
    }
});