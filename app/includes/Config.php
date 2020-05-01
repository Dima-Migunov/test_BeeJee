<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require __DIR__ . '/Settings.php';

class Config {

    /**
     * Функция, которая собирает необходимые файлы и данные для запуска проекта
     *
     * @return void
     */
    public static function init()
    {
        Settings::init();

        $files  = glob(__DIR__ . '/*.php');

        foreach($files as $item) {
            require_once $item;
        }
    }
}