<?php

namespace entities;

use InvalidArgumentException;
use PrizeInterface;

class Prize implements PrizeInterface
{
    public function __construct(
        private readonly int    $id,
        private readonly string $name,
        private readonly string $description,
        private readonly float  $value,
        private readonly float  $probability,
        private int             $quantity,
        private readonly string $type
    ) {
        if ($probability < 0 || $probability > 1) {
            throw new InvalidArgumentException('Probability must be between 0 and 1');
        }
        if ($quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getProbability(): float
    {
        return $this->probability;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    public function decrementQuantity(): bool
    {
        if ($this->quantity > 0) {
            $this->quantity--;
            return true;
        }
        return false;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative');
        }
        $this->quantity = $quantity;
    }

    public function getType(): string
    {
        return $this->type;
    }
}