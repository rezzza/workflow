<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Graph;

/**
 * PreviousOne
 *
 * @uses All
 * @uses StateInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class PreviousOne extends All implements StateInterface
{
    /**
     * {@inheritdoc}
     */
    public function isAllow(StateInterface $state, Graph $graph, $authorizationMask)
    {
        return parent::isAllow($state, $graph, $authorizationMask) && $state->getMask() === ($this->getMask() >> 1);
    }

}
