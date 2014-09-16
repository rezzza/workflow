<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Graph;

/**
 * StateInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface StateInterface
{
    /**
     * @param object $object object
     *
     * @return boolean
     */
    public function isAuthorized($object);

    /**
     * @param integer $mask mask
     */
    public function setMask($mask);

    /**
     * @return integer
     */
    public function getMask();

    /**
     * @param StateInterface $state             state
     * @param Graph          $graph             graph
     * @param integer        $authorizationMask authorizationMask
     *
     * @return boolean
     */
    public function isAllow(StateInterface $state, Graph $graph, $authorizationMask);
}
