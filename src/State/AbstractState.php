<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Graph;
use Rezzza\Workflow\Specification\SpecificationInterface;

/**
 * AbstractState
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class AbstractState
{
    /**
     * @var SpecificationInterface
     */
    private $specification;

    /**
     * @var integer
     */
    private $mask;

    /**
     * @param SpecificationInterface $specification specification
     */
    public function __construct(SpecificationInterface $specification = null)
    {
        $this->specification = $specification;
    }

    /**
     * @return boolean
     */
    public function isAuthorized($object)
    {
        if (null === $this->specification) {
            return true;
        }

        return $this->specification->isSatisfiedBy($object);
    }

    /**
     * @param integer $mask mask
     */
    public function setMask($mask)
    {
        $this->mask = $mask;
    }

    /**
     * @return integer
     */
    public function getMask()
    {
        return $this->mask;
    }
}
