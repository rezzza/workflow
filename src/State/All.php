<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Graph;

/**
 * Accepts all steps if they are in authorization mask. Excepts itself).
 *
 * @uses AbstractState
 * @uses StateInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class All extends AbstractState implements StateInterface
{
    /**
     * {@inheritdoc}
     */
    public function isAllow(StateInterface $state, Graph $graph, $authorizationMask)
    {
        return ($state->getMask() & $authorizationMask) === $state->getMask() && $state != $this;
    }
}
