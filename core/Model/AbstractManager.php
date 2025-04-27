<?php

declare(strict_types=1);

namespace Core\Model;

use Core\Data\MyPDO;
use PDO;
use PDOStatement;

/**
 * Classe abstraite Manager.
 *
 * Fournit une base pour les gestionnaires (managers) d'accès aux données.
 */
abstract class AbstractManager
{
    /**
     * Instance de PDO pour interagir avec la base de données.
     * @var MyPDO
     */
    protected $pdo;


    /**
     * Nom de la table associée au manager.
     * @var string
     */
    protected string $table;


    /**
     * Nom de l'entité associée au manager.
     * @var string
     */
    protected string $entity;




    /**
     * Constructeur de la classe Manager.
     *
     * Initialise la connexion à la base de données en utilisant l'objet MyPDO.
     */
    public function __construct()
    {


        $fqcn = get_called_class();
        $this->table = str_replace('App\Model\Manager\\', '', $fqcn);
        $this->table = strtolower(str_replace('Manager', '', $this->table));


        $entity =  'App\Model\Entity\\' . ucfirst($this->table) . 'Entity';

        if (class_exists($entity)) {
            $this->entity = $entity;
        } else {
            throw  new \Exception('La classe' . $entity . 'existe pas');
        }
        $this->pdo = MyPDO::getInstance();
    }


    /**
     * Prépare au beaoin et exécute une requête SQL avec des paramètres.
     * @param string $sql la requete SQL
     * @param array $params tableau associatif de paramètres à lier à la requête
     * @return bool|PDOStatement
     */
    protected function statement(string $sql, ?array $params = null): PDOStatement
    {
        if (is_null($params)) {
            $q = $this->pdo->query($sql);
        } else {
            $q = $this->pdo->prepare($sql);

            $q->execute($params);
        }
        return $q;
    }



    /**
     * Récupère tous les enregistrements récupéré dans le statement et les retourne sous forme d'objets entité.
     * @param \PDOStatement $q
     * @return object[]
     */
    protected function getEntities(PDOStatement $q): array
    {


        while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
            $entity = new $this->entity();
            $entity->hydrate($data);
            $entities[] = $entity;
        }

        return $entities;
    }



    /**
    * Récupère tous les enregistrements de la table associée.
    *
    * @return AbstractEntity[] Liste des entités.
    */
    public function findAll(): array
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $q = $this->statement($sql);
        return $this->getEntities($q);
    }


    /**
     * Récupère un enregistrement unique en fonction de son identifiant.
     *
     * @param int $id Identifiant unique de l'enregistrement à récupérer.
     * @return object|false Retourne une instance de l'entité correspondante ou false si non trouvée.
     */
    public function findOne(int $id): AbstractEntity|false
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE id = :id';
        $q = $this->statement($sql, [':id' => $id]);

        $data = $q->fetch(PDO::FETCH_ASSOC);


        return $data ? new $this->entity($data) : false;
    }


    /**
     * Ajoute un nouvel enregistrement dans la base de données.
     * @param array $data
     * @return AbstractEntity|false
     */
    public function add(array $data): AbstractEntity|false
    {
        $sql = 'INSERT INTO ' . $this->table . ' (';
        $sql .= implode(',', array_keys($data));
        $sql .= ') VALUES (';
        $sql .= ':' . implode(',:', array_keys($data));
        $sql .= ')';

        if ($this->statement($sql, $data)) {
            $id = $this->pdo->lastInsertId();
            return $this->findOne($id);
        } else {
            return false;
        }

    }



    /**
     * Supprime un enregistrement existant dans la base de données.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $q = $this->statement($sql, [':id' => $id]);
        return $q->rowCount() > 0;
    }


    /**
     * Met à jour un enregistrement existant dans la base de données.
     * @param int $id Identifiant de l'enregistrement à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return AbstractEntity|false Retourne l'entité mise à jour ou false en cas d'échec.
     */

    public function update(int $id, array $data): AbstractEntity |false
    {
        $sql = 'UPDATE ' . $this->table . ' SET ';
        foreach ($data as $key => $value) {
            $sql .= "$key = :$key, ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= ' WHERE id = :id';
        $data[':id'] = $id;

        if ($this->statement($sql, $data)) {
            return $this->findOne($id);
        } else {
            return false;
        }
    }

}
