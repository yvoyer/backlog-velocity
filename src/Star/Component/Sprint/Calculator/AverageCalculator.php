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
     * The array of numbers.
     *
     * @var array
     */
    private $numbers;

    /**
     * @param array $numbers
     */
    public function __construct(array $numbers)
    {
        $this->numbers = $numbers;
    }

    /**
     * Returns the average calculation.
     *
     * @return int
     */
    public function calculate()
    {
        $total = 0;
        $count = count($this->numbers); //total numbers in array
        foreach ($this->numbers as $value) {
            $total = $total + $value; // total value of array numbers
        }
        $average = ($total/$count); // get average value

        return $average;
    }
}
