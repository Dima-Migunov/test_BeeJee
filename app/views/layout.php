<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Tasks BeeJee Test</title>
</head>

<body>

    <!-- header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <span class="navbar-brand">BeeJee</span>

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Список задач</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#modal-add-task" id="button-add-task">
                            Добавить задачу
                        </a>
                    </li>
                </ul>
                <?php if(!isset($_SESSION['admin_logged'])): ?>
                    <a class="nav-link btn btn-sm btn-info text-light float-right" href="#" data-toggle="modal" data-target="#modal-login">Авторизация</a>
                <?php else: ?>
                    <a class="nav-link btn btn-sm btn-info text-light float-right" href="/?exit">Выход</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <?php if($_SESSION['error']): ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
            <?php foreach($_SESSION['error'] as $item): ?>
                <div class="alert alert-danger"><?php echo $item; ?></div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if($_SESSION['success']): ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
            <?php foreach($_SESSION['success'] as $item): ?>
                <div class="alert alert-success"><?php echo $item; ?></div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main -->
    <section class="main">
        <div class="container main__container">
            <?php include Settings::$path['views'] . $contentView; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer p-3  bg-secondary text-white">
        <div class="container ">
            <span>
                Сreated by Dmitri Migunov for BeeJee in April 2020
            </span>
        </div>
    </footer>


    <div class="modal" tabindex="-1" role="dialog" id="modal-add-task">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Новая задача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/" method="POST">
                        <input type="hidden" name="_token" value="<?php echo $_SESSION['csrf']; ?>">
                        <input type="hidden" name="id" value="" id="form-task-id">

                        <div class="form-group row mb-2">
                            <label class="col-4 col-form-label">Имя пользователя:</label>
                            <div class="col-8">
                                <input type="text" name="username" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-4 col-form-label">e-mail:</label>
                            <div class="col-8">
                                <input type="text" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-4 col-form-label">Текст:</label>
                            <div class="col-12">
                                <textarea name="text" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <div class="col-4">
                                <?php if(isset($_SESSION['admin_logged'])): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="performed">
                                    <label class="form-check-label" for="performed">Выполнено</label>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-8 text-right">
                                <button type="submit" class="btn btn-info">
                                    Сохранить
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if(!isset($_SESSION['admin_logged'])): ?>
    <div class="modal" tabindex="-1" role="dialog" id="modal-login">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Авторизация</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/" method="POST">
                        <input type="hidden" name="_token" value="<?php echo $_SESSION['csrf']; ?>">

                        <div class="form-group row mb-2">
                            <label class="col-4 col-form-label">логин:</label>
                            <div class="col-8">
                                <input type="text" name="login" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label class="col-4 col-form-label">пароль:</label>
                            <div class="col-8">
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-info">OK</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>