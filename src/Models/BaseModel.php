<?php
namespace Models;

class BaseModel {

    private \PDO $conn;
    private string $server = 'localhost';
    private string $username = 'admin';
    private string $password = 'Admin@123';
    private string $dbname = 'tecnofit';

// Create connection


    public function __construct()
    {
        try{
            $this->conn = new \PDO('mysql:host=' . $this->server . ';dbname=' . $this->dbname,$this->username,$this->password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $e){
            die('Unable to connect with the database');
        }
        $this->conn->exec('SET names utf8');
    }

    private function setParams($statement, $parameters = array()): void
    {

        foreach ($parameters as $key => $value) {

            $this->setParam($statement, $key, $value);

        }

    }

    private function setParam($statement, $key, $value): void
    {

        $statement->bindParam($key, $value);

    }

    public function query($rawQuery, $params = array()){

        $stmt = $this->conn->prepare($rawQuery);

        $this->setParams($stmt, $params);

        $stmt->execute();

        return $stmt;

    }

    public function select($rawQuery, $params = array()): array
    {

        $stmt = $this->query($rawQuery, $params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

}