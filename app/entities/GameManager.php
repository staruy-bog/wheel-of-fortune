<?php

namespace entities;

use GameManagerInterface;
use PlayerInterface;
use PrizeInterface;
use RuntimeException;
use WheelInterface;

class GameManager implements GameManagerInterface
{
    private ?PlayerInterface $currentPlayer = null;
    private ?WheelInterface $currentWheel = null;
    private array $gameResults = [];
    private array $gameSettings;

    public function __construct(array $settings = [])
    {
        $this->gameSettings = array_merge([
            'minBet' => 1.0,
            'maxBet' => 1000.0,
            'spinCost' => 10.0,
            'maxDailySpins' => 100,
            'prizeMultiplier' => 1.5,
            'bonusThreshold' => 1000
        ], $settings);
    }

    public function initGame(PlayerInterface $player, WheelInterface $wheel): bool
    {
        if (!$this->canStartGame($player)) {
            return false;
        }

        $this->currentPlayer = $player;
        $this->currentWheel = $wheel;
        return true;
    }

    public function makeMove(PlayerInterface $player): PrizeInterface
    {
        if ($this->currentPlayer !== $player || $this->currentWheel === null) {
            throw new RuntimeException('Game not initialized properly');
        }

        if (!$player->canSpin($this->gameSettings['spinCost'])) {
            throw new RuntimeException('Insufficient funds for spin');
        }

        $player->subtractBalance($this->gameSettings['spinCost']);
        $prize = $this->currentWheel->spin();

        if (!$this->currentWheel->validateSpinResult($prize)) {
            throw new RuntimeException('Invalid spin result');
        }

        return $prize;
    }

    public function processResult(PlayerInterface $player, PrizeInterface $prize): bool
    {
        if (!$prize->isAvailable()) {
            return false;
        }

        $prize->decrementQuantity();
        $player->addPrize($prize);

        if ($prize->getType() === 'money') {
            $player->addBalance($prize->getValue());
        }

        $this->saveGameResult($player, $prize);
        return true;
    }

    public function getStatistics(PlayerInterface $player): array
    {
        $playerResults = array_filter(
            $this->gameResults,
            fn($result) => $result['player']->getId() === $player->getId()
        );

        $totalSpins = count($playerResults);
        $totalWinnings = array_sum(array_map(
            fn($result) => $result['prize']->getValue(),
            $playerResults
        ));

        return [
            'totalSpins' => $totalSpins,
            'totalWinnings' => $totalWinnings,
            'averageWinning' => $totalSpins > 0 ? $totalWinnings / $totalSpins : 0,
            'lastWin' => empty($playerResults) ? null : reset($playerResults)['prize']
        ];
    }

    public function canStartGame(PlayerInterface $player): bool
    {
        $dailySpins = count(array_filter(
            $this->gameResults,
            fn($result) =>
                $result['player']->getId() === $player->getId() &&
                $result['timestamp'] > strtotime('today')
        ));

        return $player->canSpin($this->gameSettings['spinCost']) &&
            $dailySpins < $this->gameSettings['maxDailySpins'];
    }

    public function endGame(PlayerInterface $player): bool
    {
        if ($this->currentPlayer !== $player) {
            return false;
        }

        $this->currentPlayer = null;
        $this->currentWheel = null;
        return true;
    }

    public function getGameSettings(): array
    {
        return $this->gameSettings;
    }

    public function setGameSettings(array $settings): void
    {
        $this->gameSettings = array_merge($this->gameSettings, $settings);
    }

    public function saveGameResult(PlayerInterface $player, PrizeInterface $prize): bool
    {
        $this->gameResults[] = [
            'player' => $player,
            'prize' => $prize,
            'timestamp' => time()
        ];
        return true;
    }
}