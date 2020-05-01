<?php

class Helper {

    /**
     * проверка e-mail
     *
     * @param string $email
     * @return bool
     */
    public static function checkEmail(string $email)
    {
        return preg_match('/^[0-9a-zA-Z_.\-\+]{2,50}[@]{1}[0-9a-zA-Z_.\-]{2,50}[.]{1}[a-zA-Z]{2,5}$/', $email);
    }

    /**
     * Удаляем странные символы, оставляем только цифры и алфавит
     *
     * @param string $value
     * @return string
     */
    public static function only09az(string $value)
    {
        return preg_replace('#([^0-9A-Za-z])#s', '', $value);
    }

    /**
     * Перенаправление на другую страницу (redirect)
     *
     * @param string $url
     * @param boolean $permanent
     * @return void
     */
    public static function redirect(string $url='/', bool $permanent=false)
    {
        if ($permanent) {
            header('HTTP/1.1 301 Moved Permanently');
        }

        header('Location: ' . $url, FALSE);
        exit;
    }

}