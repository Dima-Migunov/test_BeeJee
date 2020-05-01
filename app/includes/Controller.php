<?php

class Controller {

    /**
     * Вывод страницы со списком задач
     *
     * @return void
     */
    public static function index()
    {
        $task   = new ModelTask();
        $view   = new View();
        $page   = 1;
        
        if(isset($_GET['page'])) {
            $page   = intval($_GET['page']);
        }

        if($page < 1) {
            $page   = 1;
        }

        $order      = self::getOrderBy();
        $direction  = 'asc';

        // жёстко проверяем значение по сортировке
        if('desc' == $_GET['direction']) {
            $direction  = 'desc';
        }

        $orderCurrent   = null;

        if($order) {
            $orderCurrent   = "&order={$order}&direction={$direction}";
        }

        $data   = [
            'tasks'     => $task->get($page, Settings::TASK_LIST_LIMIT, $order, $direction),
            'pages'     => $task->pagination($page, Settings::TASK_LIST_LIMIT),
            'orderby'   => [
                'username'  => "&order=username&direction=" . self::getDirection('username'),
                'email'     => "&order=email&direction=" . self::getDirection('email'),
                'status'    => "&order=status&direction=" . self::getDirection('status'),
                'current'   => $orderCurrent,
            ],
        ];

        $view->generate('list.php', $data);
    }

    private static function getOrderBy()
    {
        if(!isset($_GET['order'])) {
            return null;
        }

        $orderByAllowed = ['username', 'email', 'status'];

        if(in_array($_GET['order'], $orderByAllowed)) {
            return $_GET['order'];
        }

        return null;
    }

    private static function getDirection(string $order)
    {
        if($_GET['order'] == $order && 'asc' == $_GET['direction']) {
            return 'desc';
        }

        return 'asc';
    }

    /**
     * Проверка админского логина
     *
     * @return bool
     */
    public static function adminLogin()
    {
        if(!isset($_POST['login'])) {
            return false;
        }
    
        if($_POST['login'] != Settings::ADMIN_LOGIN || $_POST['password'] != Settings::ADMIN_PASSWORD) {
            $_SESSION['error'][]    = 'Логин или пароль не соответсвуют.';
            return false;
        }
    
        $_SESSION['admin_logged']   = true;

        Helper::redirect(Settings::$urlCurrent);
        exit;
    }

    /**
     * Выход из режима админа
     *
     * @return void
     */
    public static function adminExit()
    {
        unset($_SESSION['admin_logged']);
    }

    /**
     * Создать или обновить задачу
     *
     * @return bool
     */
    public static function createUpdateTask()
    {
        $task   = new ModelTask();

        if($_SESSION['csrf'] != $_POST['_token']) {
            $_SESSION['error'][]    = 'Небезопасная отправка данных. Авторизируйтесь!';
            return false;
        }

        // Далее идет обработка новой задачи или обновление задачи

        if(!isset($_POST['username']) || !isset($_POST['email']) || !$_POST['text']) {
            $_SESSION['error'][]    = 'Все поля формы должны быть заполнены';
            return false;
        }

        if(!Helper::checkEmail($_POST['email'])) {
            $_SESSION['error'][]    = 'Неправильный e-mail: ' . $_POST['email'];
            return false;
        }

        $username   = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $email      = $_POST['email'];
        $text       = htmlspecialchars($_POST['text'], ENT_QUOTES, 'UTF-8');
        $status     = 0;

        if(1 == $_POST['status']) {
            $status = 1;
        }

        if(!isset($_POST['id']) || !$_POST['id']) {
            $task->create($username, $email, $text);
            $_SESSION['success'][]  = 'Задача успешно добавлена';
            Helper::redirect();
        }
        
        // Update Task by Admin only
        if(!isset($_SESSION['admin_logged'])) {
            $_SESSION['error'][]    = 'Только админ может обновлять задачи. Авторизируйтесь!';
            Helper::redirect();
        }
        
        $id         = Helper::only09az($_POST['id']);
        $item       = $task->getById($id);
        $byAdmin    = 0;

        // текст отредактирован админом
        if($item->text != $text) {
            $byAdmin    = 1;
        }
        
        if($task->update($id, $username, $email, $text, $status, $byAdmin)) {
            $_SESSION['success'][]  = 'Задача успешно обновлена';
        } else {
            $_SESSION['error'][]    = "Задача не найдена по id={$id} и не может быть обновлена";
        }

        Helper::redirect(Settings::$urlCurrent);
        exit;
    }

    /**
     * Удалить задачу из списка
     *
     * @return void
     */
    public static function taskDelete()
    {
        if(!isset($_SESSION['admin_logged'])) {
            return;
        }

        $id     = Helper::only09az($_GET['delete']);
        $task   = new ModelTask();

        $task->delete($id);

        $_SESSION['success'][]  = 'Задача успешно удалена';

        Helper::redirect(Settings::$urlPrevious);
        exit;
    }

}