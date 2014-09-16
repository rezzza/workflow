<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Graph;

/**
 * Stay there !
 *
 * @uses AbstractState
 * @uses StateInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class End extends AbstractState implements StateInterface
{
    /**
     * {@inheritdoc}
     */
    public function isAllow(StateInterface $state, Graph $graph, $authorizationMask)
    {
        return false;
    }
}
