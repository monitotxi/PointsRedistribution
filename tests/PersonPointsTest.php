<?php

namespace PointsRedistribution\Tests;

use PHPUnit\Framework\TestCase;
use PointsRedistribution\DuplicatedItemException;
use PointsRedistribution\PersonPoints;

class PersonPointsTest extends TestCase
{
    /**
     * @var PersonPoints
     */
    private $personPoints;

    public function test_add_points()
    {
        $this->personPoints->add(1, 5);
        $this->assertSame(5.0, $this->personPoints->getTotalPoints());
    }

    public function test_start_with_no_points()
    {
        $this->assertSame(0.0, $this->personPoints->getTotalPoints());
    }

    public function test_sum_points()
    {
        $this->personPoints->add(1, 4);
        $this->personPoints->add(2, 5);
        $this->assertSame(9.0, $this->personPoints->getTotalPoints());
        $this->personPoints->add(3, 7);
        $this->assertSame(16.0, $this->personPoints->getTotalPoints());
    }

    public function test_can_add_one_time_per_item()
    {
        $this->expectException(DuplicatedItemException::class);
        $this->personPoints->add(1, 4);
        $this->personPoints->add(1, 5);
    }

    public function test_redistribute_points()
    {
        $this->personPoints->add(1, 4);
        $this->personPoints->add(2, 5);
        $this->personPoints->redistribute(2);
        $this->assertSame(9.0, $this->personPoints->getTotalPoints());
        $this->assertCount(1, $this->personPoints->getPointsByItem());
    }

    public function test_redistribute_points_proportional()
    {
        $this->personPoints->add(1, 2);
        $this->personPoints->add(2, 1);
        $this->personPoints->add(3, 3);
        $this->personPoints->redistribute(3);
        $expected = [
            1 => 4.0,
            2 => 2.0,
        ];
        $this->assertSame($expected, $this->personPoints->getPointsByItem());
    }

    public function test_add_one_point_to_all_items()
    {
        $this->personPoints->add(1, 2);
        $this->personPoints->add(2, 1);
        $this->personPoints->add(3, 3);
        $this->personPoints->addOnePointToAllItems();
        $expected = [
            1 => 3.0,
            2 => 2.0,
            3 => 4.0,
        ];
        $this->assertSame($expected, $this->personPoints->getPointsByItem());
    }

    protected function setUp()
    {
        $this->personPoints = new PersonPoints();
    }
}
