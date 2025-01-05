<?php

namespace Tests;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Entities\Wheel;
use PrizeInterface;
use RuntimeException;

class WheelTest extends TestCase
{
    private Wheel $wheel;
    private PrizeInterface|MockObject $prizeMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->wheel = new Wheel();

        // Create mock for Prize
        $this->prizeMock = $this->createMock(PrizeInterface::class);
        $this->prizeMock->method('getProbability')->willReturn(1.0);
        $this->prizeMock->method('isAvailable')->willReturn(true);
    }

    public function testSpinReturnsAvailablePrize(): void
    {
        // Arrange
        $this->wheel->addSegment($this->prizeMock);

        // Act
        $result = $this->wheel->spin();

        // Assert
        $this->assertInstanceOf(PrizeInterface::class, $result);
        $this->assertTrue($this->wheel->validateSpinResult($result));
    }

    public function testSpinThrowsExceptionWhenWheelEmpty(): void
    {
        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No segments in wheel');

        // Act
        $this->wheel->spin();
    }

    /**
     * @throws Exception
     */
    public function testSpinThrowsExceptionWhenInvalidProbability(): void
    {
        // Arrange
        $invalidPrizeMock = $this->createMock(PrizeInterface::class);
        $invalidPrizeMock->method('getProbability')->willReturn(0.0);
        $this->wheel->addSegment($invalidPrizeMock);

        // Assert
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid probability distribution');

        // Act
        $this->wheel->spin();
    }

    /**
     * @throws Exception
     */
    public function testSpinReturnsLastAvailablePrize(): void
    {
        // Arrange
        $unavailablePrizeMock = $this->createMock(PrizeInterface::class);
        $unavailablePrizeMock->method('getProbability')->willReturn(1.0);
        $unavailablePrizeMock->method('isAvailable')->willReturn(false);

        $availablePrizeMock = $this->createMock(PrizeInterface::class);
        $availablePrizeMock->method('getProbability')->willReturn(1.0);
        $availablePrizeMock->method('isAvailable')->willReturn(true);

        $this->wheel->addSegment($unavailablePrizeMock);
        $this->wheel->addSegment($availablePrizeMock);

        // Act
        $result = $this->wheel->spin();

        // Assert
        $this->assertSame($availablePrizeMock, $result);
    }
}