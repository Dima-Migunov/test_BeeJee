<?php

class Settings {
    public static $path = [
        'data'  => '/data/',
        'views' => '/app/views/',
    ];

    const ADMIN_LOGIN       = 'admin';
    const ADMIN_PASSWORD    = '123';
    const TASK_LIST_LIMIT   = 3;

    public static $urlCurrent  = null;
    public static $urlPrevious = null;

    /**
     * Функция для коррекции установочных переменных
     *
     * @return void
     */
    public static function init()
    {
        self::$path['data']     = $_SERVER['DOCUMENT_ROOT'] . self::$path['data'];
        self::$path['views']    = $_SERVER['DOCUMENT_ROOT'] . self::$path['views'];

        self::$urlCurrent   = $_SERVER['REQUEST_URI'];
        self::$urlPrevious  = $_SERVER['HTTP_REFERER'];
    }
}