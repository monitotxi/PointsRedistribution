<?php

namespace PointsRedistribution;

include '../vendor/autoload.php';

class App
{
    const NUMBER_OF_ITEMS = 5;
    private $pointsNeeded;

    public function __construct(PointsNeeded $pointsNeeded)
    {
        $this->pointsNeeded = $pointsNeeded->calculate(self::NUMBER_OF_ITEMS);
    }

    /**
     * 5 items
     * 10 points needed
     * 3 persons.
     */
    public function main()
    {
        $personPoints1 = new PersonPoints();
        $personPoints1->add(1, 1);
        $personPoints1->add(2, 6);
        $personPoints1->add(3, 1);
        $personPoints1->add(4, 0);
        $personPoints1->add(5, 2);

        $personPoints2 = new PersonPoints();
        $personPoints2->add(1, 4);
        $personPoints2->add(2, 0);
        $personPoints2->add(3, 1);
        $personPoints2->add(4, 5);
        $personPoints2->add(5, 0);

        $personPoints3 = new PersonPoints();
        $personPoints3->add(1, 10);
        $personPoints3->add(2, 0);
        $personPoints3->add(3, 0);
        $personPoints3->add(4, 0);
        $personPoints3->add(5, 0);

        if (
            $personPoints1->getTotalPoints() != $this->pointsNeeded ||
            $personPoints2->getTotalPoints() != $this->pointsNeeded ||
            $personPoints3->getTotalPoints() != $this->pointsNeeded
        ) {
            echo "Missing points!\n";
        }

        $listOfPoints = [$personPoints1, $personPoints2, $personPoints3];
        $itemPoints = new ItemPoints($listOfPoints);
        $result = $itemPoints->redistribute(2);
        $this->showResult($result, count($listOfPoints));
    }

    private function showResult(array $result, int $numberOfPersons)
    {
        $toSubtract = (self::NUMBER_OF_ITEMS * $numberOfPersons) / count($result);
        $totalPoints = 0;
        foreach ($result as $itemId => $points) {
            $finalPoints = $points - $toSubtract;
            $totalPoints += $finalPoints;
            $this->printPoints($finalPoints, $itemId);
        }
        echo "\nTotal points $totalPoints\n";
    }

    private function printPoints(float $finalPoints, int $itemId)
    {
        $roundedPoints = round($finalPoints, 2);
        echo "Item id $itemId has $roundedPoints points\n";
    }
}

$app = new App(new PointsNeeded());
$app->main();
