<?php

namespace Core\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Classe Request
 *
 * Représente une requête HTTP conforme aux standards PSR-7.
 *
 * @package Core\Http
 */
class Request extends Message implements RequestInterface
{
    protected const METHODS = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
        'HEAD',
        'OPTIONS',
        'CONNECT',
        'TRACE',
    ];

    protected string $method = 'GET';
    protected UriInterface $uri;
    protected string $requestTarget = '';

    /**
     * Constructeur de la classe Request.
     *
     * @param string $method Méthode HTTP (ex: GET, POST).
     * @param UriInterface $uri Instance d'UriInterface représentant la cible de la requête.
     *
     * @throws \InvalidArgumentException Si la méthode HTTP est invalide.
     */
    public function __construct(string $method, UriInterface $uri)
    {
        $method = strtoupper($method);

        if (!in_array($method, self::METHODS, true)) {
            throw new \InvalidArgumentException('Méthode HTTP invalide : ' . $method);
        }

        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * Récupère la cible de la requête (Request Target).
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== '') {
            return $this->requestTarget;
        }

        $path = $this->uri->getPath();

        if ($path === '') {
            $path = '/';
        }

        $query = $this->uri->getQuery();

        if ($query !== '') {
            $path .= '?' . $query;
        }

        return $path;
    }

    /**
     * Retourne une instance avec une nouvelle cible de requête.
     *
     * @param string $requestTarget
     * @return static
     */
    public function withRequestTarget($requestTarget): RequestInterface
    {
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    /**
     * Récupère la méthode HTTP de la requête.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Retourne une instance avec la méthode HTTP spécifiée.
     *
     * @param string $method
     * @return static
     *
     * @throws \InvalidArgumentException Si la méthode HTTP est invalide.
     */
    public function withMethod(string $method): RequestInterface
    {
        $method = strtoupper($method);

        if (!in_array($method, self::METHODS, true)) {
            throw new \InvalidArgumentException('Méthode HTTP invalide : ' . $method);
        }

        $new = clone $this;
        $new->method = $method;

        return $new;
    }

    /**
     * Récupère l'instance Uri associée à la requête.
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Retourne une instance avec une nouvelle instance Uri.
     *
     * @param UriInterface $uri
     * @param bool $preserveHost Indique s'il faut préserver l'hôte existant dans la requête.
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost || $this->getHost() === '') {
            if ($uri->getHost() !== '') {
                $new = $new->withHeader('Host', [$uri->getHost()]);
            }
        }

        return $new;
    }

    /**
     * Récupère l'hôte de la requête à partir des en-têtes ou de l'URI.
     *
     * @return string
     */
    protected function getHost(): string
    {
        $host = $this->getHeader('Host');

        if (!empty($host)) {
            return $host[0];
        }

        return $this->uri->getHost();
    }
}
