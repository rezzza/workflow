<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Specification\SpecificationInterface;
use Rezzza\Workflow\Graph;

/**
 * Accepts all next states until a state key. if they are in authorization mask.
 *
 * @uses All
 * @uses StateInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class NextUntil extends All implements StateInterface
{
    protected $untilKey;

    public function __construct($untilKey, SpecificationInterface $specification)
    {
        $this->untilKey = $untilKey;

        parent::__construct($specification);
    }

    /**
     * {@inheritdoc}
     */
    public function isAllow(StateInterface $state, Graph $graph, $authorizationMask)
    {
        $untilMask = $graph->getState($this->untilKey)->getMask();

        return parent::isAllow($state, $graph, $authorizationMask) && $this->getMask() < $state->getMask() && $state->getMask() <= $untilMask;
    }
}
