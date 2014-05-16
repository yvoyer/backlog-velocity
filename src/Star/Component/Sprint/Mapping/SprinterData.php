<?php
///**
// * This file is part of the backlog-velocity.
// *
// * (c) Yannick Voyer (http://github.com/yvoyer)
// */
//
//namespace Star\Component\Sprint\Mapping;
//
//use Star\Component\Sprint\Entity\Sprinter;
//use Symfony\Component\Validator\Constraint;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Validation;
//
///**
// * Class SprinterData
// *
// * @author  Yannick Voyer (http://github.com/yvoyer)
// *
// * @package Star\Component\Sprint\Mapping
// */
//class SprinterData extends Data implements Sprinter
//{
//    const LONG_NAME = __CLASS__;
//
//    /**
//     * @var integer
//     */
//    private $id;
//
//    /**
//     * The sprinter's name.
//     *
//     * @var string
//     */
//    private $name;
//
//    /**
//     * @param string $name
//     */
//    public function __construct($name)
//    {
//        $this->name = $name;
//    }
//
//    /**
//     * Returns the name.
//     *
//     * @return string
//     */
//    public function getName()
//    {
//        return $this->name;
//    }
//
//    /**
//     * Returns the unique id.
//     *
//     * @return integer
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    /**
//     * Returns the array representation of the object.
//     *
//     * @return array
//     */
//    public function toArray()
//    {
//        return array();
//    }
//
//    /**
//     * Returns the value on which to validate against.
//     *
//     * @return mixed
//     */
//    protected function getValue()
//    {
//        return $this->name;
//    }
//
//    /**
//     * @return Constraint
//     */
//    protected function getValidationConstraints()
//    {
//        return new NotBlank();
//    }
//}
