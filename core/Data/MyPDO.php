<?php

declare(strict_types=1);

namespace Core\Data;

use App\App;
use PDO;
use PDOException;

/**
 * Objet PDO personnalisé
 *
 * Cette classe étend la classe PDO pour offrir une gestion centralisée et sécurisée des connexions à la base de données.
 */
class MyPDO extends PDO
{
    /**
     * Instance unique de la classe MyPDO (singleton).
     * @var MyPDO|null
     */
    protected static ?MyPDO $instance = null;

    /**
     * Constructeur de la classe MyPDO.
     *
     * Initialise une connexion PDO avec les paramètres de configuration extraits de la classe App.
     * Gère les exceptions en cas de problème de connexion.
     */
    public function __construct()
    {
        $dsn = 'mysql:host=' . App::get('DB_HOST') . ';dbname=' . App::get('DB_NAME') . ';charset=utf8';

        try {
            parent::__construct($dsn, App::get('DB_USER'), App::get('DB_PASS'));
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'UTF8'");
        } catch (PDOException $e) {
            $msg =  'Problème avec la base de données';

            if (defined('DEV') && DEV === true) {
                $msg .= $e->getMessage();
            }

            throw new \Exception('Erreur de connexion à la base de données : ' . $msg);
        }
    }

    /**
     * Retourne l'instance unique de la classe MyPDO (implémentation du singleton).
     *
     * @return MyPDO L'instance unique de la classe.
     */
    public static function getInstance(): MyPDO
    {
        if (self::$instance === null) {
            self::$instance = new MyPDO();
        }

        return self::$instance;
    }
}
