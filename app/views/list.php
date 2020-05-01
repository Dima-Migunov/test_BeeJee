<div class="container" id="task-list">
    <div class="row">
        <div class="col-12 text-right">
            tasks: <?php echo $data['pages']['amount']; ?>
        </div>
    </div>

    <div class="row text-left mb-2">
        <div class="col-12">
            Сортировать по

            <a href="/?page=1<?php echo $data['orderby']['username']; ?>" class="btn btn-sm btn-warning ml-2 mr-2">
                Имени пользователя
            </a>
            
            <a href="/?page=1<?php echo $data['orderby']['email']; ?>" class="btn btn-sm btn-warning mr-2">
                email
            </a>
            
            <a href="/?page=1<?php echo $data['orderby']['status']; ?>" class="btn btn-sm btn-warning mr-2">
                Статусу
            </a>
        </div>
    </div>

    <?php foreach($data['tasks'] as $i=>$task): ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card" data-id="<?php echo $task['id']; ?>" data-status="<?php echo $task['status']; ?>">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Пользователь: <span class="badge badge-info badge-user"><?php echo $task['username']; ?></span>
                            e-Mail: <span class="badge badge-info badge-email"><?php echo $task['email']; ?></span>
                        </div>
        
                        <div class="col-3 text-center">
                        <?php if(isset($_SESSION['admin_logged'])): ?>
                            <button type="button" class="btn btn-sm btn-info btn-edit" data-toggle="modal" data-target="#modal-add-task">
                                Редактировать
                            </button>
                            
                            <a href="/?delete=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger btn-delete">
                                Удалить
                            </a>
                        <?php endif; ?>
                        </div>
                        
                        <div class="col-3 text-right">
                            Статус:
                            <?php if(0 == $task['status']): ?>
                                <span class="badge badge-warning">не выполнено</span>
                            <?php else: ?>
                                <span class="badge badge-success">выполнено</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body"><pre><?php echo $task['text']; ?></pre></div>
                
                <?php if(1 == $task['byAdmin']): ?>
                <div class="card-footer text-right">
                    <span class="badge badge-danger">
                        Отредактировано админом
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="row mt-3 mb-5">
        <div class="col-12 text-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php for($i=1; $i<=$data['pages']['pages']; $i++): ?>
                    <li class="page-item">
                        <a class="page-link" href="/?page=<?php echo $i; ?><?php echo $data['orderby']['current']; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>