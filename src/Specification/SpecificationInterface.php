<?php

namespace Rezzza\Workflow\Specification;

/**
 * SpecificationInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface SpecificationInterface
{
    /**
     * @param object $object object
     *
     * @return boolean
     */
    public function isSatisfiedBy($object);
}
