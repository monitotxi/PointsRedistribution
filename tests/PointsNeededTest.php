<?php

namespace PointsRedistribution\Tests;

use PHPUnit\Framework\TestCase;
use PointsRedistribution\PointsNeeded;

class PointsNeededTest extends TestCase
{
    /**
     * @dataProvider dataSet
     *
     * @param int $numberOfPoints
     * @param int $numberOfElements
     */
    public function test_calculate_needed_points(int $numberOfPoints, int $numberOfElements)
    {
        $pointsNeeded = new PointsNeeded();
        $result = $pointsNeeded->calculate($numberOfElements);
        $this->assertSame($numberOfPoints, $result);
    }

    public function dataSet()
    {
        return [
            [0, 0],
            [0, 1],
            [5, 2],
            [5, 3],
            [10, 4],
            [10, 5],
            [15, 6],
            [25, 7],
            [30, 8],
            [40, 9],
            [45, 10],
            [55, 11],
            [70, 12],
        ];
    }
}
