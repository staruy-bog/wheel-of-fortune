<?php

namespace entities;

use InvalidArgumentException;
use PlayerInterface;
use PrizeInterface;
use RuntimeException;

class Player implements PlayerInterface
{
    private array $prizeHistory = [];

    public function __construct(
        private readonly int    $id,
        private readonly string $username,
        private float           $balance = 0.0
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function addBalance(float $amount): bool
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }
        $this->balance += $amount;
        return true;
    }

    public function subtractBalance(float $amount): bool
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Amount cannot be negative');
        }
        if ($this->balance < $amount) {
            throw new RuntimeException('Insufficient funds');
        }
        $this->balance -= $amount;
        return true;
    }

    public function canSpin(float $spinCost): bool
    {
        return $this->balance >= $spinCost;
    }

    public function getPrizeHistory(int $limit = 10): array
    {
        return array_slice($this->prizeHistory, 0, $limit);
    }

    public function addPrize(PrizeInterface $prize): bool
    {
        array_unshift($this->prizeHistory, $prize);
        return true;
    }

    public function exchangePrizeForPoints(PrizeInterface $prize): float
    {
        $points = $prize->getValue() * 0.5; // 50% від вартості призу
        $this->addBalance($points);
        return $points;
    }
}