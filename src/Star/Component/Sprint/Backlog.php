<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint;

/**
 * Class Backlog
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint
 */
class Backlog
{
    /**
     * @var Sprint[]
     */
    private $aSprints = array();

    /**
     * Add the $sprint.
     *
     * @param \Star\Component\Sprint\Sprint $sprint
     */
    public function addSprint(Sprint $sprint)
    {
        $this->aSprints[] = $sprint;
    }

    /**
     * Returns the estimated velocity for the sprint based on stats from previous sprints.
     *
     * @param integer $availableManDays The available man days for the sprint
     *
     * @return integer The estimated velocity in story point
     */
    public function calculateEstimatedVelocity($availableManDays)
    {
        $focus = 70;
        if (false === empty($this->aSprints)) {
            $focus = $this->getEstimatedFocusFactor();
        }

        return (int) floor(($availableManDays * $focus) / 100);
    }

    /**
     * Returns the estimated focus factor based on past sprints.
     *
     * @return float
     */
    private function getEstimatedFocusFactor()
    {
        $aPastFocus = array();
        foreach ($this->aSprints as $sprint) {
            $aPastFocus[] = $sprint->getFocusFactor();
        }

        return $this->calculateAverage($aPastFocus);
    }

    /**
     * Calculate the average of $values in array.
     *
     * @param array $values
     *
     * @return float
     */
    private function calculateAverage(array $values)
    {
        $total = 0;
        $count = count($values); //total numbers in array
        foreach ($values as $value) {
            $total = $total + $value; // total value of array numbers
        }
        $average = ($total/$count); // get average value

        return $average;
    }
}
