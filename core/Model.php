<?php

namespace app\core;

use app\core\DB;

class Model
{
    protected  $table;
    protected $fillable = [];
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }


    public function all()
    {
        $db = Database::$db;
        $stmt = $db->query("SELECT * FROM $this->table ");
        $data = $stmt->fetchAll($db->pdo::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return $data;
    }

    public function find($id)
    {
        $db = Database::$db;
        $stmt = $db->query("SELECT * FROM $this->table WHERE id = ?", [$id]);
        $data = $stmt->fetch($db->pdo::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return $data;
    }

    public function save()
    {
        $params = [];
        $fields = [];


        foreach ($this->fillable as $field) {
            $fields[] = $field . ' = ?';
            $params[] = $this->$field;
        }

        if ($this->id) {
            // Update the record
            $query = 'UPDATE ' . "$this->table" . ' SET ' . implode(', ', $fields) . ' WHERE id = ?';
        } else {
            // Insert a new record
            $query = 'INSERT INTO ' . "$this->table" . ' SET ' . implode(', ', $fields);
        }

        $stmt = Database::$db->pdo->prepare($query);
        $stmt->execute($params);

        // If it's a new record, set the id property
        if (!$this->id) {
            $this->id = Database::$db->pdo->lastInsertId();
        }
    }

    public function destroy($id)
    {
        $db = Database::$db;
        $stmt = $db->query("DELETE FROM $this->table WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    }

    public function registerUser()
    {
        $db = Database::$db;
        $stmt = $db->query(
            "INSERT INTO users (username, email, password) VALUES(?,?,?)",
            [$this->username, $this->email, $this->hashed_password]
        );
    }


    public function loginUser()
    {
        $db = Database::$db;
        $stmt = $db->query("SELECT * FROM users WHERE email= ?", [$this->email]);
        $user = $stmt->fetch();
        if ($user && password_verify($this->password, $user['password'])) {
            Session::setSession(['username' => $user['username'], 'email' => $this->email]);
            return true;
        } else {
            return false;
        }
    }

    public function logoutUser()
    {
        session_start();
        session_destroy();
        header("Location: /");
    }
}
