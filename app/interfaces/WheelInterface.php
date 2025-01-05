<?php

/**
 * Interface WheelInterface
 *
 * Інтерфейс для управління колесом та його сегментами
 */
interface WheelInterface {
    /**
     * Отримати всі сегменти колеса
     *
     * @return array Масив об'єктів PrizeInterface
     */
    public function getSegments(): array;

    /**
     * Встановити нові сегменти для колеса
     *
     * @param array $segments Масив об'єктів PrizeInterface
     * @return void
     * @throws \InvalidArgumentException якщо сегменти невалідні
     */
    public function setSegments(array $segments): void;

    /**
     * Додати новий сегмент до колеса
     *
     * @param PrizeInterface $prize Приз для нового сегмента
     * @param int $position Позиція (опціонально)
     * @return bool
     */
    public function addSegment(PrizeInterface $prize, ?int $position = null): bool;

    /**
     * Видалити сегмент з колеса
     *
     * @param int $position Позиція сегмента
     * @return bool
     */
    public function removeSegment(int $position): bool;

    /**
     * Зробити спін колеса та отримати результат
     *
     * @return PrizeInterface
     * @throws \RuntimeException якщо виникла помилка при спіні
     */
    public function spin(): PrizeInterface;

    /**
     * Перевірити валідність результату спіну
     *
     * @param PrizeInterface $prize Виграний приз
     * @return bool
     */
    public function validateSpinResult(PrizeInterface $prize): bool;

    /**
     * Отримати поточні налаштування колеса
     *
     * @return array
     */
    public function getSettings(): array;

    /**
     * Встановити налаштування колеса
     *
     * @param array $settings Масив налаштувань
     * @return void
     */
    public function setSettings(array $settings): void;
}
