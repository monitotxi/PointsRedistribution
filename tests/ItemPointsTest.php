<?php

namespace PointsRedistribution\Tests;

use PHPUnit\Framework\TestCase;
use PointsRedistribution\ItemPoints;
use PointsRedistribution\PersonPoints;

class ItemPointsTest extends TestCase
{
    private $john = [
        'points' => [
            1 => 1,
            2 => 1,
            3 => 0,
        ],
    ];
    private $jack = [
        'points' => [
            1 => 3,
            2 => 0,
            3 => 5,
        ],
    ];
    private $jerry = [
        'points' => [
            1 => 4,
            2 => 1,
            3 => 1,
        ],
    ];

    /**
     * @dataProvider dataSetForSum
     *
     * @param array $listOfPoints
     * @param array $expected
     */
    public function test_get_total_points_by_item(
        array $listOfPoints,
        array $expected
    ) {
        $itemPoints = new ItemPoints($listOfPoints);
        $totalPointsByItem = $itemPoints->getTotalPointsByItem();
        $this->assertSame($expected, $totalPointsByItem);
    }

    /**
     * @dataProvider dataSetForRedistribute
     *
     * @param array $listOfPoints
     * @param array $expected
     */
    public function test_redistribute_all_to_one_item(
        array $listOfPoints,
        array $expected
    ) {
        $itemPoints = new ItemPoints($listOfPoints);
        $totalPointsByItem = $itemPoints->redistribute(1);
        $this->assertSame($expected, $totalPointsByItem);
    }

    public function dataSetForSum(): array
    {
        $set1 = [
            $this->getListOfPoints([$this->john, $this->jack]),
            [
                3 => 5.0,
                1 => 4.0,
                2 => 1.0,
            ],
        ];
        $set2 = [
            $this->getListOfPoints([$this->john, $this->jack, $this->jerry]),
            [
                1 => 8.0,
                3 => 6.0,
                2 => 2.0,
            ],
        ];
        $set3 = [
            $this->getListOfPoints([$this->john, $this->jerry]
            ),
            [
                1 => 5.0,
                2 => 2.0,
                3 => 1.0,
            ],
        ];
        $set4 = [$this->getListOfPoints([$this->jack, $this->jerry]),
            [
                1 => 7.0,
                3 => 6.0,
                2 => 1.0,
            ],
        ];

        return [
            $set1,
            $set2,
            $set3,
            $set4,
        ];
    }

    private function getListOfPoints(array $personsData): array
    {
        $listOfPoints = [];
        foreach ($personsData as $personData) {
            $listOfPoints[] = $this->getPersonPoints($personData['points']);
        }

        return $listOfPoints;
    }

    private function getPersonPoints(array $pointsPerItem): PersonPoints
    {
        $personPoints = new PersonPoints();
        foreach ($pointsPerItem as $itemId => $points) {
            $personPoints->add($itemId, $points);
        }

        return $personPoints;
    }

    public function dataSetForRedistribute(): array
    {
        $set1 = [
            $this->getListOfPoints([$this->john, $this->jack]),
            [
                3 => 16.0,
            ],
        ];
        $set2 = [
            $this->getListOfPoints([$this->john, $this->jack, $this->jerry]),
            [
                1 => 25.0,
            ],
        ];
        $set3 = [
            $this->getListOfPoints([$this->john, $this->jerry]
            ),
            [
                1 => 14.0,
            ],
        ];
        $set4 = [$this->getListOfPoints([$this->jack, $this->jerry]),
            [
                1 => 20.0,
            ],
        ];

        return [
            $set1,
            $set2,
            $set3,
            $set4,
        ];
    }
}
