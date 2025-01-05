<?php

/**
 * Interface PrizeInterface
 *
 * Інтерфейс для управління призами в системі "Колесо Фортуни"
 */
interface PrizeInterface {
    /**
     * Отримати унікальний ідентифікатор призу
     *
     * @return int
     */
    public function getId(): int;

    /**
     * Отримати назву призу
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Отримати опис призу
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Отримати вартість призу
     *
     * @return float
     */
    public function getValue(): float;

    /**
     * Отримати ймовірність випадання призу (від 0 до 1)
     *
     * @return float
     */
    public function getProbability(): float;

    /**
     * Отримати кількість доступних призів даного типу
     *
     * @return int
     */
    public function getQuantity(): int;

    /**
     * Перевірити чи приз доступний для видачі
     *
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * Зменшити кількість доступних призів на 1
     *
     * @return bool true якщо операція успішна, false якщо призів більше немає
     */
    public function decrementQuantity(): bool;

    /**
     * Встановити нову кількість призів
     *
     * @param int $quantity Нова кількість призів
     * @return void
     * @throws \InvalidArgumentException якщо quantity < 0
     */
    public function setQuantity(int $quantity): void;

    /**
     * Отримати тип призу (virtual/physical/money)
     *
     * @return string
     */
    public function getType(): string;
}