<?php

declare(strict_types=1);

namespace Core\Http;

/**
 * Interface MessageInterface
 *
 * Représente un message HTTP générique.
 * Peut être utilisé pour les requêtes (Request) ou les réponses (Response).
 *
 * Fournit les méthodes standards pour accéder et manipuler :
 * - La version du protocole HTTP
 * - Les en-têtes HTTP
 * - Le corps du message
 */
interface MessageInterface
{
    /**
     * Récupère la version du protocole HTTP.
     *
     * @return string La version du protocole HTTP (ex : "1.1", "2.0").
     */
    public function getProtocolVersion(): string;

    /**
     * Retourne une instance avec la version du protocole spécifiée.
     *
     * Ce changement doit respecter le principe d'immuabilité.
     *
     * @param string $version La nouvelle version du protocole HTTP.
     * @return static Une nouvelle instance avec la version modifiée.
     */
    public function withProtocolVersion(string $version): self;

    /**
     * Récupère tous les en-têtes du message.
     *
     * @return array Un tableau associatif des en-têtes.
     */
    public function getHeaders(): array;

    /**
     * Vérifie la présence d'un en-tête donné.
     *
     * @param string $name Le nom de l'en-tête.
     * @return bool True si l'en-tête existe, false sinon.
     */
    public function hasHeader(string $name): bool;

    /**
     * Récupère toutes les valeurs associées à un en-tête donné.
     *
     * @param string $name Le nom de l'en-tête.
     * @return array Les valeurs associées à l'en-tête sous forme de tableau.
     */
    public function getHeader(string $name): array;

    /**
     * Retourne une instance avec l'en-tête remplacé par une nouvelle valeur.
     *
     * Ce changement doit respecter le principe d'immuabilité.
     *
     * @param string $name Le nom de l'en-tête.
     * @param string|array $value La ou les nouvelles valeurs de l'en-tête.
     * @return static Une nouvelle instance avec l'en-tête modifié.
     */
    public function withHeader(string $name, $value): self;

    /**
     * Retourne une instance sans l'en-tête spécifié.
     *
     * Ce changement doit respecter le principe d'immuabilité.
     *
     * @param string $name Le nom de l'en-tête à supprimer.
     * @return static Une nouvelle instance sans l'en-tête.
     */
    public function withoutHeader(string $name): self;

    /**
     * Récupère le corps du message.
     *
     * @return mixed Le corps du message (typage précis à définir plus tard, ex : StreamInterface).
     */
    public function getBody();

    /**
     * Retourne une instance avec un nouveau corps de message.
     *
     * Ce changement doit respecter le principe d'immuabilité.
     *
     * @param mixed $body Le nouveau corps du message.
     * @return static Une nouvelle instance avec le corps modifié.
     */
    public function withBody($body): self;


    /**
     * Récupère toutes les valeurs d'un en-tête sous forme d'une chaîne, séparées par des virgules.
     *
     * @param string $name Nom de l'en-tête.
     * @return string
     */
    public function getHeaderLine(string $name): string;

    /**
     * Retourne une instance avec une valeur ajoutée à un en-tête existant, ou créé si absent.
     *
     * @param string $name Nom de l'en-tête.
     * @param string|array $value Valeur(s) à ajouter.
     * @return static
     */
    public function withAddedHeader(string $name, $value): static;
}
