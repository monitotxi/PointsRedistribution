<?php

namespace PointsRedistribution;

class ItemPoints
{
    private $listOfPoints;

    public function __construct(array $listOfPoints)
    {
        $this->listOfPoints = $listOfPoints;
    }

    public function redistribute(int $numberOfItems)
    {
        /** @var PersonPoints $personPoints */
        foreach ($this->listOfPoints as $personPoints) {
            $personPoints->addOnePointToAllItems();
        }
        while (count($this->getTotalPointsByItem()) > $numberOfItems) {
            $this->removeOneItemAndRedistributePoints();
        }

        return $this->getTotalPointsByItem();
    }

    public function getTotalPointsByItem(): array
    {
        $totalPointsByItem = [];
        /** @var PersonPoints $personPoints */
        foreach ($this->listOfPoints as $personPoints) {
            $this->aggregatePointsFromAPerson($personPoints, $totalPointsByItem);
        }
        arsort($totalPointsByItem);

        return $totalPointsByItem;
    }

    private function aggregatePointsFromAPerson(PersonPoints $personPoints, array &$totalPointsByItem)
    {
        $pointsByItem = $personPoints->getPointsByItem();
        foreach ($pointsByItem as $itemId => $points) {
            if (isset($totalPointsByItem[$itemId])) {
                $totalPointsByItem[$itemId] += $points;
            } else {
                $totalPointsByItem[$itemId] = $points;
            }
        }
    }

    private function removeOneItemAndRedistributePoints()
    {
        $itemIdToRemove = $this->getItemIdToRemove();
        /** @var PersonPoints $personPoints */
        foreach ($this->listOfPoints as $personPoints) {
            $this->redistributePointsOfAPerson($personPoints, $itemIdToRemove);
        }
    }

    private function getItemIdToRemove(): int
    {
        $totalPointsByItem = $this->getTotalPointsByItem();
        $keys = array_keys($totalPointsByItem);

        return array_pop($keys);
    }

    private function redistributePointsOfAPerson(PersonPoints $personPoints, int $itemIdToRemove)
    {
        $pointsByItem = $personPoints->getPointsByItem();
        foreach ($pointsByItem as $itemId => $points) {
            if ($itemId == $itemIdToRemove) {
                $personPoints->redistribute($itemId);
            }
        }
    }
}
