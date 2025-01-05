<?php

/**
 * Interface PlayerInterface
 *
 * Інтерфейс для управління гравцями в системі
 */
interface PlayerInterface {
    /**
     * Отримати унікальний ідентифікатор гравця
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Отримати ім'я гравця
     *
     * @return string
     */
    public function getUsername(): string;

    /**
     * Отримати поточний баланс гравця
     *
     * @return float
     */
    public function getBalance(): float;

    /**
     * Додати кошти до балансу гравця
     *
     * @param float $amount Сума для додавання
     * @return bool
     * @throws \InvalidArgumentException якщо amount < 0
     */
    public function addBalance(float $amount): bool;

    /**
     * Зняти кошти з балансу гравця
     *
     * @param float $amount Сума для зняття
     * @return bool
     * @throws \InvalidArgumentException якщо amount < 0
     * @throws \RuntimeException якщо недостатньо коштів
     */
    public function subtractBalance(float $amount): bool;

    /**
     * Перевірити чи може гравець зробити спін
     *
     * @param float $spinCost Вартість спіну
     * @return bool
     */
    public function canSpin(float $spinCost): bool;

    /**
     * Отримати історію виграшів гравця
     *
     * @param int $limit Кількість останніх записів
     * @return array Масив об'єктів PrizeInterface
     */
    public function getPrizeHistory(int $limit = 10): array;

    /**
     * Додати приз до історії гравця
     *
     * @param PrizeInterface $prize Виграний приз
     * @return bool
     */
    public function addPrize(PrizeInterface $prize): bool;

    /**
     * Обміняти приз на бали
     *
     * @param PrizeInterface $prize Приз для обміну
     * @return float Кількість нарахованих балів
     */
    public function exchangePrizeForPoints(PrizeInterface $prize): float;
}