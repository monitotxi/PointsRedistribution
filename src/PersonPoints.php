<?php

namespace PointsRedistribution;

class PersonPoints
{
    private $pointsByItem = [];

    public function add(int $itemId, float $points)
    {
        if (isset($this->pointsByItem[$itemId])) {
            throw new DuplicatedItemException('Item with id '.$itemId.' is already in the collection');
        }
        $this->pointsByItem[$itemId] = $points;
    }

    public function getPointsByItem(): array
    {
        return $this->pointsByItem;
    }

    public function redistribute(int $itemIdToRemove)
    {
        $pointsToRedistribute = $this->pointsByItem[$itemIdToRemove];
        $totalPoints = $this->getTotalPoints() - $pointsToRedistribute;
        foreach ($this->pointsByItem as $itemId => $points) {
            if ($itemId != $itemIdToRemove) {
                $this->pointsByItem[$itemId] += $this->getPointsToAdd($points, $totalPoints, $pointsToRedistribute);
            }
        }
        unset($this->pointsByItem[$itemIdToRemove]);
    }

    public function getTotalPoints(): float
    {
        $totalPoints = 0;
        foreach ($this->pointsByItem as $points) {
            $totalPoints += $points;
        }

        return $totalPoints;
    }

    private function getPointsToAdd(float $points, float $totalPoints, float $pointsToRedistribute): float
    {
        $percentage = ($points * 100) / $totalPoints;

        return ($percentage * $pointsToRedistribute) / 100;
    }

    public function addOnePointToAllItems()
    {
        $func = function (&$value) {
            $value += 1;
        };
        array_walk($this->pointsByItem, $func);
    }
}
