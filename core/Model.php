<?php

namespace app\core;

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


    public function all($search = null)
    {

        $where = '';
        $params = array();
        $data = [];
        if ($search) {
            if (is_array($search)) {
                $conditions = array();
                foreach ($search as $field => $term) {
                    $conditions[] = "$field LIKE ?";
                    $params[] = "%$term%";
                }
                $where = 'WHERE ' . implode(' || ', $conditions);
                $query = "SELECT * FROM $this->table $where";
            } else {
                $data = [];
            }
        } else {
            $query = "SELECT * FROM $this->table";
        }


        $stmt = Database::$db->query($query, $params);
        $data = $stmt->fetchAll(Database::$db->pdo::FETCH_ASSOC);

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

        $stmt = Database::$db->query($query, $params);

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

    public function registerUser($errors)
    {
        $db = Database::$db;
        $stmt = $db->query(
            "INSERT INTO users (username, email, password) VALUES(?,?,?)",
            [$this->username, $this->email, $this->hashed_password]
        );
        if (empty(array_filter($errors))) {
            Session::setSession(['username' => $this->username, 'email' => $this->email]);
            header('Location: /');
        }
    }


    public function loginUser($errors)
    {
        $invalidLogin = '';
        $db = Database::$db;
        $stmt = $db->query("SELECT * FROM users WHERE email= ?", [$this->email]);
        $user = $stmt->fetch();
        if (empty(array_filter($errors))) {
            if ($user && password_verify($this->password, $user['password'])) {
                Session::setSession(['username' => $user['username'], 'email' => $this->email]);
                header("Location: /");
            } else {
                $errors['credential_err'] = 'Invalid email or password.';
                $invalidLogin = $errors['credential_err'];
            }
        }
        return $invalidLogin;
    }

    public function logoutUser()
    {
        session_start();
        session_destroy();
        header("Location: /");
    }
}
