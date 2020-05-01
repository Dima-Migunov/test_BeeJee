<?php

class ModelTask {

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int [1 = выполнено]
     */
    public $status  = 0;

    /**
     * @var int [1 = редактировано админом]
     */
    public $byAdmin = 0;

    /**
     * @var int timestamp
     */
    public $created_at;

    /**
     * @var int timestamp
     */
    public $updated_at;

    private $table  = 'tasks.json';
    private $data   = [];

    function __construct()
    {
        $this->table    = Settings::$path['data'] . $this->table;

        if(file_exists($this->table)) {
            $this->data = file_get_contents($this->table);
            $this->data = json_decode($this->data, true);
        }
    }
        
    /**
     * function: create new Entry
     *
     * @param  string $username
     * @param  string $email
     * @param  string $text
     * @return bool
     */
    public function create(string $username, string $email, string $text)
    {
        $entry  = [
            'id'            => sha1($username . $email . $text . time()),
            'username'      => $username,
            'email'         => $email,
            'text'          => $text,
            'status'        => 0,
            'byAdmin'       => 0,
            'created_at'    => time(),
            'updated_at'    => time(),
        ];

        $this->data[]   = $entry;

        return $this->saveData($entry);
    }

    /**
     * function: update Entry
     *
     * @param string $id
     * @param string $username
     * @param string $email
     * @param string $text
     * @param integer $status
     * @param integer $byAdmin
     * @return void
     */
    public function update(string $id, string $username, string $email, string $text, int $status=0, int $byAdmin=0)
    {
        foreach($this->data as $i=>$row) {
            if($row['id'] != $id) {
                continue;
            }

            $entry  = [
                'id'            => $id,
                'username'      => $username,
                'email'         => $email,
                'text'          => $text,
                'status'        => $status,
                'byAdmin'       => $byAdmin,
                'created_at'    => $row['created_at'],
                'updated_at'    => time(),
            ];
    
            $this->data[$i] = $entry;
    
            return $this->saveData($entry);
        }

        return false;
    }

    /**
     * Удалить задачу из списка
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id)
    {
        foreach($this->data as $i=>$row) {
            if($row['id'] == $id) {
                unset($this->data[$i]);
                return $this->saveData();
            }    
        }

        return false;
    }

    private function saveData(array $entry=null)
    {
        if($entry) {
            foreach($entry as $key=>$value) {
                $this->{$key}   = $value;
            }
        }

        $data   = json_encode($this->data);

        try{
            file_put_contents($this->table, $data);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * function get Data
     *
     * @param integer $offset
     * @param integer $limit
     * @param string $orderBy=null
     * @return array
     */
    public function get(int $page=1, int $limit=3, string $orderBy=null, string $direction='asc')
    {
        $page--;

        if($page < 0) {
            $page   = 0;
        }

        $offset = $page * Settings::TASK_LIST_LIMIT;
        $data   = $this->orderBy($this->data, $orderBy, $direction);
        $data   = array_slice($data, $offset, $limit);

        return $data;
    }

    private function orderBy(array $data, string $orderBy=null, string $direction='asc')
    {
        if(!$orderBy) {
            return $data;
        }

        if('username' == $orderBy) {
            uasort($data, function($a, $b) use ($direction){
                if('desc' == $direction) {
                    return strcasecmp($a['username'], $b['username'])*(-1);
                }

                return strcasecmp($a['username'], $b['username']);
            });
        }

        if('email' == $orderBy) {
            uasort($data, function($a,$b) use ($direction){
                if('desc' == $direction) {
                    return strcasecmp($a['email'], $b['email'])*(-1);
                }

                return strcmp($a['email'], $b['email']);
            });
        }

        if('status' == $orderBy) {
            uasort($data, function($a,$b) use ($direction){
                if('desc' == $direction) {
                    return ($a['status'] > $b['status']);
                }

                return ($a['status'] < $b['status']);
            });
        }

        return $data;
    }

    /**
     * Данные для пагинации
     *
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function pagination(int $page, int $limit=3)
    {
        $data   = [
            'amount'    => count($this->data),
            'current'   => $page,
            'limit'     => $limit,
        ];

        $data['pages']  = ceil($data['amount'] / $limit);

        return $data;
    }

    /**
     * получить задачу из спика по ID
     *
     * @param string $id
     * @return array
     */
    public function getById(string $id)
    {
        foreach($this->data as $row) {
            if($row['id'] == $id) {
                return $row;
            }    
        }

        return null;
    }
}