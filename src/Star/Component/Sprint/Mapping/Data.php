<?php
/**
 * This file is part of the backlog-velocity project.
 * 
 * (c) Yannick Voyer (http://github.com/yvoyer)
 */

namespace Star\Component\Sprint\Mapping;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

/**
 * Class Data
 *
 * @author  Yannick Voyer (http://github.com/yvoyer)
 *
 * @package Star\Component\Sprint\Mapping
 *
 * todo remove
 */
abstract class Data
{
    /**
     * Returns whether the data is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validateValue(
            $this->getValue(),
            $this->getValidationConstraints()
        );

        return ($violations->count() === 0);
    }

    /**
     * Returns the value on which to validate against.
     *
     * @return mixed
     */
    protected abstract function getValue();

    /**
     * @return Constraint
     */
    protected abstract function getValidationConstraints();
}
