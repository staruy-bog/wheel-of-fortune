<?php

use entities\Prize;
use PHPUnit\Framework\TestCase;

class PrizeTest extends TestCase
{
    private Prize $prize;

    protected function setUp(): void
    {
        $this->prize = new Prize(
            id: 1,
            name: "Test Prize",
            description: "Test Description",
            value: 100.0,
            probability: 0.5,
            quantity: 10,
            type: "virtual"
        );
    }

    /**
     * @test
     */
    public function it_should_correctly_decrement_quantity(): void
    {
        // Arrange
        $initialQuantity = $this->prize->getQuantity();

        // Act
        $result = $this->prize->decrementQuantity();
        $newQuantity = $this->prize->getQuantity();

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($initialQuantity - 1, $newQuantity);
    }

    /**
     * @test
     */
    public function it_should_return_false_when_decrementing_empty_quantity(): void
    {
        // Arrange
        $this->prize->setQuantity(0);

        // Act
        $result = $this->prize->decrementQuantity();

        // Assert
        $this->assertFalse($result);
        $this->assertEquals(0, $this->prize->getQuantity());
    }

    /**
     * @test
     */
    public function it_should_correctly_check_availability(): void
    {
        // Arrange & Act & Assert
        $this->assertTrue($this->prize->isAvailable());

        $this->prize->setQuantity(0);
        $this->assertFalse($this->prize->isAvailable());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_for_negative_quantity(): void
    {
        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        $this->prize->setQuantity(-1);
    }

    /**
     * @test
     */
    public function it_should_correctly_get_prize_properties(): void
    {
        // Assert
        $this->assertEquals(1, $this->prize->getId());
        $this->assertEquals("Test Prize", $this->prize->getName());
        $this->assertEquals("Test Description", $this->prize->getDescription());
        $this->assertEquals(100.0, $this->prize->getValue());
        $this->assertEquals(0.5, $this->prize->getProbability());
        $this->assertEquals("virtual", $this->prize->getType());
    }
}