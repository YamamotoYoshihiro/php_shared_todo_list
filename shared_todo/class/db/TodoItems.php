<?php
require_once('Base.php');
class TodoItems extends Base
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }
    public function selectId()
    {
        $sql = 'SELECT id, user FROM users';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $listId = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $listId;
    }

    /**
     * Undocumented function
     *
     * @param [int] $id
     * @return TODOリストに登録したものの一つ
     */
    public function selectTodoItem($id)
    {
        $sql = 'SELECT todo_items.id, users.user, todo_items.user_id, todo_items.item_name, todo_items.expire_date, 
        todo_items.finished_date, todo_items.is_deleted  
        FROM users LEFT OUTER JOIN todo_items on users.id = todo_items.user_id
        WHERE todo_items.id=:id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $listTodoItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $listTodoItem;
    }
    public function selectJoin()
    {
        $sql = 'SELECT *
        FROM users LEFT OUTER JOIN todo_items on users.id = todo_items.user_id 
        where todo_items.is_deleted=0 order by expire_date ASC';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($list);
        return $list;
    }
    public function updateById($id, $finished_date)
    {
        $sql = 'UPDATE todo_items SET finished_date=:finished_date WHERE id=:id';
        // var_dump($sql);
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":finished_date", $finished_date, PDO::PARAM_STR);
        $stmt->execute();
        var_dump($finished_date);
    }
    public function insert($user_id, $item_name, $registration_date, $expire_date, $finished_date)
    {
        $sql = "INSERT INTO todo_items(user_id, item_name, registration_date, expire_date, finished_date) 
        VALUES (:user_id, :item_name, :registration_date, :expire_date, :finished_date)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":item_name", $item_name, PDO::PARAM_STR);
        $stmt->bindValue(":registration_date", $registration_date, PDO::PARAM_STR);
        $stmt->bindValue(":expire_date", $expire_date, PDO::PARAM_STR);
        $stmt->bindValue(":finished_date", $finished_date, PDO::PARAM_STR);
        $stmt->execute();
    }
    public function update($id, $user_id, $item_name, $expire_date, $finished_date)
    {
        $sql = 'UPDATE todo_items SET user_id=:user_id, item_name=:item_name, expire_date=:expire_date, finished_date=:finished_date
        WHERE id=:id';

        // -- users LEFT OUTER JOIN todo_items on users.id = todo_items.user_id 
        // -- set todo_items.id=:id, todo_items.user_id, todo_items.item_name=:item_name, todo_items.expire_date=:expire_date, 
        // -- todo_items.finished_date=:finished_date WHERE todo_items.id=:id';
        $stmt = $this->dbh->prepare($sql);
        // var_dump($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':item_name', $item_name, PDO::PARAM_STR);
        $stmt->bindValue(":expire_date", $expire_date, PDO::PARAM_STR);
        $stmt->bindValue(":finished_date", $finished_date, PDO::PARAM_STR);
        $stmt->execute();
        // var_dump($stmt->execute());
    }
    public function updateByDeleted($id, $is_deleted)
    {
        $sql = 'UPDATE todo_items SET is_deleted=1, id=:id WHERE id=:id';
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':is_deleted', $is_deleted, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function search($keywordSearch)
    {
        $sql = "SELECT *
        FROM users LEFT OUTER JOIN todo_items on users.id = todo_items.user_id 
        WHERE $keywordSearch AND todo_items.is_deleted=0 order by expire_date ASC";
        // var_dump($sql);
        $stmt = $this->dbh->prepare($sql);
        // $stmt->bindValue(":keywordSearch", $keywordSearch, PDO::PARAM_STR);
        $stmt->execute();
        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($list);
        return $list;
    }
}
