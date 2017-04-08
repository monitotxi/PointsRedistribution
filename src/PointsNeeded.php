<?php

namespace PointsRedistribution;

class PointsNeeded
{
    const ROUND_TO = 5;

    public function calculate(int $numberOfItems): int
    {
        $points = $this->getNeededPoints($numberOfItems);

        return $this->getMultipleOf($points);
    }

    private function getNeededPoints(int $numberOfItems): int
    {
        $points = -$numberOfItems;
        while ($numberOfItems > 0) {
            $points += $numberOfItems;
            --$numberOfItems;
        }

        return $points;
    }

    private function getMultipleOf(int $points): int
    {
        return (int) ceil($points / self::ROUND_TO) * self::ROUND_TO;
    }
}
