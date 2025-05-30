<?php

namespace Core\Database;
use Core\Database\PdoFactory;
use PDO;

class AbstractRepository
{
    protected string $table;
    protected string $entityClassName;
    protected ?PDO $pdo = null;

    public function __construct(string $table, string $entityClassName, PDO $pdo)
    {
        $this->table = $table;
        $this->entityClassName = $entityClassName;
        $this->pdo = $pdo;
        
    }

    public function statement(string $sql, array $params = []): \PDOStatement
    {
      
       if(!empty($params)){
            $statement = $this->pdo->query($sql);
       }else{
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
       }
        if ($statement === false) {
            throw new \Exception('Error executing statement: ' . implode(', ', $this->pdo->errorInfo()));
        }
        return $statement;
    }
    

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $statement = $this->statement($sql);
        return $statement->fetchAll(PDO::FETCH_CLASS, $this->entityClassName);
    }

    public function findById(int $id): ?object
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $statement = $this->statement($sql, ['id' => $id]);
        return $statement->fetchObject($this->entityClassName);
    }
    public function findBy(array $criteria): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $conditions = [];
        foreach ($criteria as $key => $value) {
            $conditions[] = "$key = :$key";
        }
        $sql .= implode(' AND ', $conditions);
        $statement = $this->statement($sql, $criteria);
        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->entityClassName);
    }
    public function save(object $entity): int
    {
        $data = (array)$entity;
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(',:', array_keys($data));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $statement = $this->statement($sql, $data);
        return $this->pdo->lastInsertId();
       
    }

    public function update(object $entity)
    {
       // on verra plus tard
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $statement = $this->statement($sql, ['id' => $id]);
        return $statement->rowCount() > 0;
    }


}