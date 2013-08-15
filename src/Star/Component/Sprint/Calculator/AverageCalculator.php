<?php
/**
 * This file is part of the backlog-velocity.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Calculator;

/**
 * Class AverageCalculator
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Calculator
 */
class AverageCalculator
{
    /**
     * Returns the average calculation.
     *
     * @param array $numbers
     *
     * @return int
     */
    public function calculateAverage(array $numbers)
    {
        $average = 0;
        if (false === empty($numbers)) {
            $total = 0;
            $count = count($numbers); //total numbers in array
            foreach ($numbers as $value) {
                $total = $total + $value; // total value of array numbers
            }
            $average = ($total/$count); // get average value
        }

        return $average;
    }
}
