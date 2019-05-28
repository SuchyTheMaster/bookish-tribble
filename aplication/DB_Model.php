<?php

class DB_Model
{
    private $db_settings;
    private $db;
    private $tableNamePrefix;
    private $usersAccTableName;
    private $articlesTableName;

    public function __construct($db_settings)
    {
        $this->db_settings = $db_settings;
        $this->connect();

        $this->tableNamePrefix = 'Xrqb3LP2_';
        $this->usersAccTableName = $this->tableNamePrefix . 'users_acc';
        $this->articlesTableName = $this->tableNamePrefix . 'news';
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    private function connect()
    {
        try
        {
            $this->db = new PDO($this->db_settings['dsn'] .
                ':host=' . $this->db_settings['hostname'] .
                ';dbname=' . $this->db_settings['database'] .
                ';charset=' . $this->db_settings['char_set']
                , $this->db_settings['username']
                , $this->db_settings['password']);
        }
        catch (PDOException $e)
        {
            echo 'database connection error.' . $e->getMessage();;
            exit;
        }
    }

    private function disconnect(): void
    {
        $this->db = null;
    }

    /* USERS ACC begin */

    public function getAllUsersAcc()
    {
        $result = $this->db->query('SELECT * FROM ' . $this->usersAccTableName);

        $users = $result->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public function getUserAccById($id)
    {
        $query = 'SELECT * FROM ' . $this->usersAccTableName . ' WHERE id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public function getUserAccByEmail($email)
    {
        $query = 'SELECT * FROM ' . $this->usersAccTableName . ' WHERE email = :email';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public function insertUserAcc($user): int
    {
        $query = 'INSERT INTO ' . $this->usersAccTableName . ' (first_name, last_name, email, gender, is_active, password, created_at, updated_at) VALUES (:first_name, :last_name, :email, :gender, :is_active, :password, :created_at, :updated_at)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':first_name', $user['first_name'], PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $user['last_name'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $user['email'], PDO::PARAM_STR);
        $stmt->bindValue(':gender', $user['gender'], PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $user['is_active'], PDO::PARAM_BOOL);
        $stmt->bindValue(':password', $user['password'], PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $user['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', $user['updated_at'], PDO::PARAM_STR);
        $stmt->execute();

        $id = $this->db->lastInsertId();

        return $id;
    }

    public function deleteUserAcc($id): bool
    {

        $query = 'DELETE FROM ' . $this->usersAccTableName . ' WHERE id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
        {
            return true;
        }

        return false;
    }

    /* USERS ACC end */

    /* NEWS begin */

    public function getAllArticles()
    {
        $result = $this->db->query(''
            . 'SELECT n.id, n.name, n.description, n.is_active, n.created_at, n.updated_at, n.author_id, u.first_name, u.last_name '
            . 'FROM ' . $this->articlesTableName . ' n  LEFT JOIN ' . $this->usersAccTableName . ' u '
            . 'ON n.author_id = u.id ');

        $users = $result->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public function getArticleById($id)
    {
        $query = 'SELECT n.id, n.name, n.description, n.is_active, n.created_at, n.updated_at, n.author_id, u.first_name, u.last_name FROM ' . $this->articlesTableName . ' AS n LEFT JOIN ' . $this->usersAccTableName . ' AS u ON (n.author_id = u.id) WHERE n.id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        return $article;
    }

    public function insertArticle($article): int
    {
        $query = 'INSERT INTO ' . $this->articlesTableName . ' (name, description, is_active, created_at, updated_at, author_id) '
            . 'VALUES (:name, :description, :is_active, :created_at, :updated_at, :author_id)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $article['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $article['description'], PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $article['is_active'], PDO::PARAM_BOOL);
        $stmt->bindValue(':created_at', $article['created_at'], PDO::PARAM_STR);
        $stmt->bindValue(':updated_at', $article['updated_at'], PDO::PARAM_STR);
        $stmt->bindValue(':author_id', $article['author_id'], PDO::PARAM_INT);
        $stmt->execute();

        $id = $this->db->lastInsertId();

        return $id;
    }

    public function updateArticle($article): bool
    {
        $query = 'UPDATE ' . $this->articlesTableName . ' '
            . 'SET name = :name, description = :description, is_active = :is_active, updated_at = :updated_at '
            . 'WHERE id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $article['name'], PDO::PARAM_STR);
        $stmt->bindValue(':description', $article['description'], PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $article['is_active'], PDO::PARAM_BOOL);
        $stmt->bindValue(':updated_at', $article['updated_at'], PDO::PARAM_STR);
        $stmt->bindValue(':id', $article['id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
        {
            return true;
        }

        return false;
    }

    public function deleteArticle($id): bool
    {
        $query = 'DELETE FROM ' . $this->articlesTableName . ' WHERE id = :id';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
        {
            return true;
        }

        return false;
    }

    /* NEWS end */
}