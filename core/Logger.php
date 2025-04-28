<?php

declare(strict_types=1);

namespace Core;

use Psr\Log\LoggerInterface;
use Stringable;

/**
 * Classe Logger
 *
 * Fournit une implémentation simple d'un système de journalisation conforme à la norme PSR-3.
 *
 * Cette classe permet :
 * - L'enregistrement des événements de l'application selon différents niveaux de gravité
 *   (emergency, alert, critical, error, warning, notice, info, debug).
 * - La gestion automatique de deux fichiers de log distincts :
 *   - Un fichier pour les erreurs (`error.log`).
 *   - Un fichier pour les accès et informations (`access.log`).
 * - La création automatique du dossier de logs en environnement de développement si nécessaire.
 * - Le formatage standardisé des lignes de log (date ISO 8601, niveau, message).
 *
 * Fonctionnalités principales :
 * - Validation stricte des niveaux de log autorisés.
 * - Compatibilité avec les objets `Stringable` pour les messages.
 * - Gestion des erreurs d'écriture avec des exceptions explicites.
 *
 * Exemple de format de ligne de log :
 * [2024-04-27T15:10:00+02:00] [ERROR] Une erreur critique est survenue.
 *
 * @package Core
 */
class Logger implements LoggerInterface
{
    /**
     * Constantes de configuration pour le Logger.
     *
     * Fichiers de log :
     * - self::ERROR_FILE  → Fichier dédié aux logs d'erreurs (error.log).
     * - self::ACCESS_FILE → Fichier dédié aux logs d'accès ou d'information générale (access.log).
     *
     * Niveaux de gravité disponibles (PSR-3) :
     * - self::EMERGENCY → Système inutilisable.
     * - self::ALERT     → Action immédiate requise.
     * - self::CRITICAL  → Erreur critique affectant le système.
     * - self::ERROR     → Erreur classique ne nécessitant pas d'intervention immédiate.
     * - self::WARNING   → Avertissement, attention particulière.
     * - self::NOTICE    → Événement notable mais non problématique.
     * - self::INFO      → Information générale sur le déroulement de l'application.
     * - self::DEBUG     → Détail d'exécution pour le débogage.
     *
     * Liste complète des niveaux acceptés :
     * - self::LEVELS → Tableau contenant tous les niveaux valides.
     *
     * Ces constantes permettent :
     * - De sécuriser l'utilisation des niveaux de log sans faute de frappe.
     * - D'améliorer l'auto-complétion et la lisibilité du code.
     * - De valider les niveaux de log dans la méthode log().
     */

    protected const  ERROR_FILE = 'error.log';
    protected const  ACCESS_FILE = 'access.log';

    protected const  ERROR = 'error';
    protected const  WARNING = 'warning';
    protected const  INFO = 'info';
    protected const  DEBUG = 'debug';
    protected const  EMERGENCY = 'emergency';
    protected const  ALERT = 'alert';
    protected const  CRITICAL = 'critical';
    protected const  NOTICE = 'notice';


    protected const LEVELS = [
        self::EMERGENCY,
        self::ALERT,
        self::CRITICAL,
        self::ERROR,
        self::WARNING,
        self::NOTICE,
        self::INFO,
        self::DEBUG,
    ];



    /**
     * Logger constructor.
     *
     * Vérifie l'existence du dossier de logs défini par la constante `LOG`.
     *
     * - En mode développement (DEV) :
     *   - Si le dossier `LOG` n'existe pas et que son dossier parent est accessible en écriture,
     *     le dossier est automatiquement créé avec des permissions 0777.
     *   - Sinon, une exception est levée pour indiquer un problème de permissions.
     *
     * - En mode production (PROD) :
     *   - Si le dossier `LOG` n'existe pas, une exception est systématiquement levée,
     *     afin d'éviter toute création non contrôlée en environnement de production.
     *
     * @throws \Exception Si le dossier ne peut pas être créé ou n'existe pas selon l'environnement.
     */
    public function __construct()
    {
        if (!is_dir(LOG)) {
            if (DEV) {
                if (!is_writable(dirname(LOG))) {
                    throw new \Exception('Je ne peux pas créer le dossier (probleme de permissions.');
                }
                mkdir(LOG, 0777, true);
            } else {
                throw new \Exception('Le dossier de log n\'existe pas et ne peut pas être créé.');
            }
        }
    }


    /**
     * Écrit un message dans le fichier de log.
     *
     * Le message est formaté avec :
     * - La date et l'heure actuelles au format ISO 8601 (ex : 2024-04-27T14:52:00+02:00).
     * - Le niveau de gravité en majuscules (ex : ERROR, INFO, DEBUG).
     * - Le contenu du message passé en paramètre.
     *
     * Exemple de ligne de log :
     * [2024-04-27T14:52:00+02:00] [ERROR] Erreur critique rencontrée.
     *
     * Si le fichier de log n'existe pas, il est créé automatiquement.
     * Les nouveaux messages sont ajoutés à la suite du fichier existant (mode append).
     *
     * @param string $level   Le niveau de gravité du message (ex : 'error', 'info', 'debug', etc.).
     * @param string $message Le message à enregistrer dans le fichier de log.
     *
     * @return void
     *
     * @throws \RuntimeException Si l'écriture dans le fichier échoue.
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        if (!in_array($level, self::LEVELS, true)) {
            throw new \InvalidArgumentException(sprintf('Niveau de log invalide : %s', (string) $level));
        }


        // Pour l'instant, ignorer $context
        $entry = sprintf(
            "[%s] [%s] %s\n",
            (new \DateTime())->format('Y-m-d\TH:i:sP'),
            strtoupper((string) $level),
            (string) $message
        );

        if ($level !== self::INFO) {
            $file = LOG . '/' . self::ERROR_FILE;
        } else {
            $file = LOG . '/' . self::ACCESS_FILE;
        }

        // on ouvre le fichier en mode append
        $handle = fopen($file, 'a');
        if ($handle === false) {
            throw new \RuntimeException('Impossible d\'ouvrir le fichier de log.');
        }

        // on écrit le message dans le fichier
        if (fwrite($handle, $entry) === false) {
            fclose($handle);
            throw new \RuntimeException('Impossible d\'écrire dans le fichier de log.');
        }

        fclose($handle);
    }

    /**
     * Enregistre un message d'urgence (système inutilisable).
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Enregistre un message d'alerte (action immédiate requise).
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->log(self::ALERT, $message, $context);
    }

    /**
     * Enregistre un message critique (conditions critiques).
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Enregistre un message d'erreur.
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function error(string|Stringable $message, array $context = []): void
    {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Enregistre un message d'avertissement.
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->log(self::WARNING, $message, $context);
    }

    /**
     * Enregistre un message d'information notable (notice).
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Enregistre un message informatif.
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function info(string|Stringable $message, array $context = []): void
    {
        $this->log(self::INFO, $message, $context);
    }

    /**
     * Enregistre un message de débogage.
     *
     * @param string|Stringable $message Le message à enregistrer.
     * @param array $context Données contextuelles.
     */
    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->log(self::DEBUG, $message, $context);
    }
}
