<?php

declare(strict_types=1);

namespace Core\Model;

/**
 * Classe abstraite représentant une entité.
 *
 * Fournit les propriétés et méthodes de base pour gérer les entités, notamment l'identifiant,
 * les dates de création et de mise à jour, ainsi qu'une méthode d'hydratation.
 */
abstract class AbstractEntity
{
    /**
     * Identifiant unique de l'entité.
     * @var int
     */
    protected ?int $id;


    /**
     * Hydrate l'entité avec les données fournies.
     *
     * @param array $data Les données à injecter dans l'entité.
     * @return self
     * @throws \Exception Si une propriété ne correspond pas à un setter existant.
     */
    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            // Convertit param_key -> paramKey
            $camelKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $method = 'set' . ucfirst($camelKey);

            if (method_exists(static::class, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }


    /**
    * Convertit l'entité en tableau associatif.
    *
    * @return array Le tableau associatif représentant l'entité.
    */
    public function toArray(): array
    {
        $vars = get_object_vars($this);
        $result = [];

        foreach ($vars as $key => $value) {
            // camelCase -> snake_case
            $snakeKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key));
            $result[$snakeKey] = $value;
        }

        return $result;
    }


    /**
     * Récupère l'identifiant de l'entité.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Définit l'identifiant de l'entité.
     *
     * @param int $id Identifiant de l'entité.
     * @return self
     * @throws \InvalidArgumentException Si l'identifiant n'est pas strictement positif.
     */
    public function setId($id): self
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException("L'identifiant doit être strictement positif");
        }
        $this->id = $id;

        return $this;
    }
}
