<?php

declare(strict_types=1);

namespace Core\Http;

/**
 * Classe Message
 *
 * Représente un message HTTP générique, conforme aux bases de PSR-7.
 *
 * Cette classe permet de gérer :
 * - La version du protocole HTTP (par exemple 1.0, 1.1, 2)
 * - Les en-têtes HTTP sous forme de tableau
 * - Le corps du message (body)
 *
 * Chaque modification (ex: changement de protocole, ajout ou suppression d'un en-tête, changement de corps)
 * suit le principe d'immutabilité :
 * - Les méthodes `with*` retournent **toujours** une nouvelle instance modifiée
 * - L'instance d'origine reste inchangée
 *
 * Cette classe sert de base pour construire des objets plus spécifiques comme `Request` ou `Response`.
 *
 * @package Core\Http
 */
class Message implements MessageInterface
{
    /**
     * Summary of HTTP_VERSIONS
     * @var array
     */
    protected const HTTP_VERSIONS = ['1.0', '1.1', '2', '2.0', '3'];

    /**
     * Version du protocole HTTP (ex: "1.1", "2.0").
     *
     * @var string
     */
    protected string $protocolVersion = '1.1';

    /**
     * Tableau associatif des en-têtes HTTP.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Corps du message HTTP.
     *
     * @var mixed
     */
    protected $body;

    /**
     * Récupère la version du protocole HTTP.
     *
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Retourne une instance avec la version du protocole modifiée.
     *
     * @param string $version
     * @return static
     */
    public function withProtocolVersion(string $version): static
    {

        if (!in_array($version, self::HTTP_VERSIONS, true)) {
            throw new \InvalidArgumentException('Version HTTP non supportée : ' . $version);
        }

        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    /**
     * Récupère tous les en-têtes HTTP.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Vérifie si un en-tête existe.
     *
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Récupère toutes les valeurs associées à un en-tête donné.
     *
     * @param string $name
     * @return array
     */
    public function getHeader(string $name): array
    {
        if (!$this->hasHeader($name)) {
            return [];
        }

        return $this->headers[$name];
    }

    /**
     * Retourne une instance avec un en-tête remplacé.
     *
     * @param string $name
     * @param string|array $value
     * @return static
     *
     */
    public function withHeader(string $name, $value): static
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Le nom de l\'en-tête ne doit pas être vide.');
        }

        if (!is_string($value) && !is_array($value)) {
            throw new \InvalidArgumentException('La valeur de l\'en-tête doit être une chaîne ou un tableau.');
        }

        $new = clone $this;
        $new->headers[$name] = array_map('strval', (array) $value);

        return $new;
    }

    /**
     * Retourne une instance sans un en-tête donné.
     *
     * @param string $name
     * @return static
     */
    public function withoutHeader(string $name): static
    {
        if (!$this->hasHeader($name)) {
            return $this;
        }

        $new = clone $this;
        unset($new->headers[$name]);

        return $new;
    }

    /**
     * Récupère le corps du message HTTP.
     *
     * @return mixed
     */
    public function getBody(): mixed
    {
        return $this->body;
    }

    /**
     * Retourne une instance avec un nouveau corps.
     *
     * @param mixed $body
     * @return static
     */
    public function withBody($body): static
    {
        if ($body === null) {
            throw new \InvalidArgumentException('Le corps ne doit pas être vide.');
        }
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    /**
     * Récupère toutes les valeurs d'un en-tête sous forme d'une chaîne, séparées par des virgules.
     *
     * @param string $name
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        $values = $this->getHeader($name);
        return implode(', ', $values);
    }

    /**
     * Retourne une instance avec une valeur ajoutée à un en-tête existant, ou créé si absent.
     *
     * @param string $name
     * @param string|array $value
     * @return static
     */
    public function withAddedHeader(string $name, $value): static
    {
        $new = clone $this;
        $existing = $new->headers[$name] ?? [];

        $value = is_array($value) ? $value : [$value];

        $new->headers[$name] = array_merge($existing, $value);

        return $new;
    }
}
