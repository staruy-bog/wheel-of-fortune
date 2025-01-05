<?php

/**
 * Interface GameManagerInterface
 *
 * Інтерфейс для управління ігровим процесом
 */
interface GameManagerInterface {
    /**
     * Ініціалізувати нову гру
     *
     * @param PlayerInterface $player Гравець
     * @param WheelInterface $wheel Колесо
     * @return bool
     */
    public function initGame(PlayerInterface $player, WheelInterface $wheel): bool;

    /**
     * Зробити хід (спін)
     *
     * @param PlayerInterface $player Гравець
     * @return PrizeInterface Виграний приз
     * @throws \RuntimeException якщо гра не ініціалізована
     */
    public function makeMove(PlayerInterface $player): PrizeInterface;

    /**
     * Обробити результат гри
     *
     * @param PlayerInterface $player Гравець
     * @param PrizeInterface $prize Виграний приз
     * @return bool
     */
    public function processResult(PlayerInterface $player, PrizeInterface $prize): bool;

    /**
     * Отримати статистику гри
     *
     * @param PlayerInterface $player Гравець
     * @return array
     */
    public function getStatistics(PlayerInterface $player): array;

    /**
     * Перевірити можливість початку нової гри
     *
     * @param PlayerInterface $player Гравець
     * @return bool
     */
    public function canStartGame(PlayerInterface $player): bool;

    /**
     * Завершити поточну гру
     *
     * @param PlayerInterface $player Гравець
     * @return bool
     */
    public function endGame(PlayerInterface $player): bool;

    /**
     * Отримати налаштування гри
     *
     * @return array
     */
    public function getGameSettings(): array;

    /**
     * Встановити налаштування гри
     *
     * @param array $settings Масив налаштувань
     * @return void
     */
    public function setGameSettings(array $settings): void;

    /**
     * Зберегти результат гри
     *
     * @param PlayerInterface $player Гравець
     * @param PrizeInterface $prize Виграний приз
     * @return bool
     */
    public function saveGameResult(PlayerInterface $player, PrizeInterface $prize): bool;
}
