<?php

class View {

    /**
     * function generate
     *
     * @param string $contentView   - Шаблон для представления данных страницы
     * @param array  $data          - Данные для страницы
     * @param string $layout        - Общий вид страницы
     * @return void
     */
    function generate(string $contentView, array $data = null, string $layout='layout.php'){
        $_SESSION['csrf']   = sha1(uniqid(time()));

        include_once Settings::$path['views'] . $layout;

        $_SESSION['error']      = [];
        $_SESSION['success']    = [];
    }
}
