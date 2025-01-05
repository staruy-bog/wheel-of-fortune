<?php

namespace entities;

use InvalidArgumentException;
use PrizeInterface;
use RuntimeException;
use WheelInterface;

class Wheel implements WheelInterface
{
    private array $segments = [];
    private array $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = array_merge([
            'segments' => 12,
            'minRotation' => 720,
            'maxRotation' => 1440,
            'spinDuration' => 5000,
            'animation' => 'ease-out'
        ], $settings);
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    public function setSegments(array $segments): void
    {
        foreach ($segments as $segment) {
            if (!$segment instanceof PrizeInterface) {
                throw new InvalidArgumentException('All segments must implement PrizeInterface');
            }
        }
        $this->segments = $segments;
    }

    public function addSegment(PrizeInterface $prize, ?int $position = null): bool
    {
        if ($position !== null) {
            if ($position < 0 || $position > count($this->segments)) {
                throw new InvalidArgumentException('Invalid position');
            }
            array_splice($this->segments, $position, 0, [$prize]);
        } else {
            $this->segments[] = $prize;
        }
        return true;
    }

    public function removeSegment(int $position): bool
    {
        if ($position < 0 || $position >= count($this->segments)) {
            return false;
        }
        array_splice($this->segments, $position, 1);
        return true;
    }

    public function spin(): PrizeInterface
    {
        if (empty($this->segments)) {
            throw new RuntimeException('No segments in wheel');
        }

        $totalProbability = array_sum(array_map(
            fn(PrizeInterface $prize) => $prize->getProbability(),
            $this->segments
        ));

        if ($totalProbability <= 0) {
            throw new RuntimeException('Invalid probability distribution');
        }

        $random = mt_rand() / mt_getrandmax() * $totalProbability;
        $currentProbability = 0;

        foreach ($this->segments as $prize) {
            $currentProbability += $prize->getProbability();
            if ($random <= $currentProbability && $prize->isAvailable()) {
                return $prize;
            }
        }

        // Якщо всі призи закінчились, повертаємо останній доступний
        foreach ($this->segments as $prize) {
            if ($prize->isAvailable()) {
                return $prize;
            }
        }

        throw new RuntimeException('No available prizes');
    }

    public function validateSpinResult(PrizeInterface $prize): bool
    {
        return in_array($prize, $this->segments, true) && $prize->isAvailable();
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = array_merge($this->settings, $settings);
    }
}