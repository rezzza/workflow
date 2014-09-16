<?php

namespace Rezzza\Workflow\State;

use Rezzza\Workflow\Specification\SpecificationInterface;
use Rezzza\Workflow\Graph;

/**
 * Accepts all states defined on constructor. if they are in authorization mask.
 *
 * @uses All
 * @uses StateInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class StateCollection extends All implements StateInterface
{
    /**
     * @var array
     */
    protected $stateKeys;

    /**
     * @param array                  $stateKeys     stateKeys
     * @param SpecificationInterface $specification specification
     */
    public function __construct(array $stateKeys, SpecificationInterface $specification)
    {
        if (empty($stateKeys)) {
            throw new \InvalidArgumentException('StateCollection expects a collection of state keys');
        }

        $this->stateKeys = $stateKeys;

        parent::__construct($specification);
    }

    /**
     * {@inheritdoc}
     */
    public function isAllow(StateInterface $state, Graph $graph, $authorizationMask)
    {
        $acceptedMasks = 0;
        foreach ($this->stateKeys as $stateKey) {
            $acceptedMasks |= $graph->getState($stateKey)->getMask();
        }

        return parent::isAllow($state, $graph, $authorizationMask) && ($state->getMask() & $acceptedMasks) === $state->getMask();
    }
}
