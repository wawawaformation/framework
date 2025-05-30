<?php

declare(strict_types=1);

namespace Core\Database;

use Core\Logger;
use PDO;
use PDOException;

/**
 * Classe de fabrique pour créer une instance de PDO.
 *
 * @package Core\Database
 */
class PdoFactory
{

    protected static ?PDO $instance = null;

    /**
     * Crée une instance de PDO à partir des variables d'environnement.
     *
     * @param Logger $logger
     * @return PDO
     * @throws \RuntimeException
     */
    public static function fromEnv(Logger $logger): PDO
    {
        try {
            $dsn = $_ENV['DB_SGBDR'] . ':host=' . $_ENV['DB_HOST'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $pass = $_ENV['DB_PASS'];

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            ]);

            return self::$instance;
        } catch (\Throwable $e) {
            $logger->critical('Connexion à la base échouée : ' . $e->getMessage());
            throw new \RuntimeException("Impossible de se connecter à la base de données");
        }
    }

    /**
     * Retourne l'instance de PDO.
     * @throws \RuntimeException
     * @return PDO|null
     */
    public static function getInstance(): PDO
    {
        if (!isset(self::$instance)) {
            throw new \RuntimeException("La connexion PDO n'a pas été initialisée.");
        }
        return self::$instance;
    }
}
