<?php

declare(strict_types=1);

namespace Core\Http;

use Psr\Http\Message\UriInterface;

/**
 * Classe Uri
 *
 * Représente une URI (Uniform Resource Identifier) conforme aux standards PSR-7.
 * Permet d'accéder à chaque partie de l'URI et de générer une nouvelle instance immuable lors de modifications.
 *
 * Composants typiques :
 * - Scheme (http, https)
 * - UserInfo (user:password)
 * - Host (exemple.com)
 * - Port (80, 443)
 * - Path (/blog/article)
 * - Query (id=42&comment=12)
 * - Fragment (#section1)
 *
 * @package Core\Http
 */
class Uri implements UriInterface
{
    protected string $scheme = '';
    protected string $userInfo = '';
    protected string $host = '';
    protected ?int $port = null;
    protected string $path = '';
    protected string $query = '';
    protected string $fragment = '';

    /**
     * Récupère le schéma (ex: http, https).
     *
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Retourne une instance avec le schéma modifié.
     *
     * @param string $scheme
     * @return static
     */
    public function withScheme($scheme): UriInterface
    {
        $new = clone $this;
        $new->scheme = strtolower($scheme);
        return $new;
    }

    /**
     * Récupère l'information utilisateur (ex: user:password).
     *
     * @return string
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * Retourne une instance avec l'information utilisateur modifiée.
     *
     * @param string $user
     * @param string|null $password
     * @return static
     */
    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $new = clone $this;
        $new->userInfo = $user;
        if ($password !== null) {
            $new->userInfo .= ':' . $password;
        }
        return $new;
    }

    /**
     * Récupère l'hôte (ex: www.exemple.com).
     *
     * @return string
     */
    public function getHost(): string
    {
        return strtolower($this->host);
    }

    /**
     * Retourne une instance avec l'hôte modifié.
     *
     * @param string $host
     * @return static
     */
    public function withHost($host): UriInterface
    {
        $new = clone $this;
        $new->host = strtolower($host);
        return $new;
    }

    /**
     * Récupère le port de l'URI.
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        if ($this->port === null) {
            return null;
        }

        if (($this->scheme === 'http' && $this->port === 80) ||
            ($this->scheme === 'https' && $this->port === 443)) {
            return null;
        }

        return $this->port;
    }

    /**
     * Retourne une instance avec le port modifié.
     *
     * @param int|null $port
     * @return static
     */
    public function withPort($port): UriInterface
    {
        $new = clone $this;
        $new->port = $port;
        return $new;
    }

    /**
     * Récupère le chemin de l'URI.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Retourne une instance avec le chemin modifié.
     *
     * @param string $path
     * @return static
     */
    public function withPath($path): UriInterface
    {
        $new = clone $this;
        $new->path = $path;
        return $new;
    }

    /**
     * Récupère la chaîne de requête de l'URI (query string).
     *
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Retourne une instance avec la requête modifiée.
     *
     * @param string $query
     * @return static
     */
    public function withQuery($query): UriInterface
    {
        $new = clone $this;
        $new->query = $query;
        return $new;
    }

    /**
     * Récupère le fragment de l'URI.
     *
     * @return string
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Retourne une instance avec le fragment modifié.
     *
     * @param string $fragment
     * @return static
     */
    public function withFragment($fragment): UriInterface
    {
        $new = clone $this;
        $new->fragment = $fragment;
        return $new;
    }

    /**
     * Récupère l'autorité (userInfo + host + port) de l'URI.
     *
     * @return string
     */
    public function getAuthority(): string
    {
        $authority = '';

        if ($this->userInfo !== '') {
            $authority .= $this->userInfo . '@';
        }

        $authority .= $this->host;

        if ($this->port !== null) {
            if (($this->scheme === 'http' && $this->port !== 80) ||
                ($this->scheme === 'https' && $this->port !== 443)) {
                $authority .= ':' . $this->port;
            } elseif (!in_array($this->scheme, ['http', 'https'], true)) {
                $authority .= ':' . $this->port;
            }
        }

        return $authority;
    }

    /**
     * Récupère l'URI complète sous forme de chaîne de caractères.
     *
     * @return string
     */
    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        if ($this->getAuthority() !== '') {
            $uri .= '//' . $this->getAuthority();
        }

        $path = $this->path;

        if ($path !== '') {
            if ($this->getAuthority() !== '' && str_starts_with($path, '/') === false) {
                $path = '/' . $path;
            }
        }

        $uri .= $path;

        if ($this->query !== '') {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }
}
