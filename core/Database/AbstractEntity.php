<?php

declare(strict_types=1);

namespace Core\Database;

use DateTimeImmutable;
use ReflectionMethod;
use ReflectionNamedType;

class AbstractEntity
{
    protected ?int $id = null;
    protected ?DateTimeImmutable $createdAt = null;
    protected ?DateTimeImmutable $updatedAt = null;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    /**
     * Hydrate dynamiquement l’entité à partir d’un tableau associatif.
     *
     * Si une valeur est une chaîne représentant une date, elle sera convertie
     * en DateTimeImmutable si le setter attend ce type.
     *
     * @param array $data Données à injecter.
     * @return static
     */
    public function hydrate(array $data): static
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $reflection = new ReflectionMethod($this, $method);
                $params = $reflection->getParameters();

                if (
                    isset($params[0]) &&
                    $params[0]->getType() instanceof ReflectionNamedType &&
                    $params[0]->getType()->getName() === DateTimeImmutable::class &&
                    is_string($value)
                ) {
                    $value = new DateTimeImmutable($value);
                }

                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Retourne un tableau des propriétés publiques (utile pour du debug ou JSON).
     */
    public function getArrayCopy(): array
    {
        $data = [];
        $reflection = new \ReflectionObject($this);

        do {
            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($this);

                if ($value instanceof \DateTimeInterface) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                $data[$property->getName()] = $value;
            }

            $reflection = $reflection->getParentClass();
        } while ($reflection);

        return $data;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    // --- Getters et Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
