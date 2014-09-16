<?php

namespace Rezzza\Workflow;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Workflow
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Workflow
{
    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var object
     */
    private $object;

    /**
     * @var string
     */
    private $field;

    /**
     * @param Graph  $graph    graph
     * @param object $object   object
     * @param string $property property
     */
    public function __construct(Graph $graph, $object, $property)
    {
        $this->graph    = $graph;
        $this->object   = $object;
        $this->property = $property;
    }

    /**
     * Returns authorized states for an object using the state mapping.
     *
     * @return array(<string>)
     */
    public function getAuthorizedStates()
    {
        $authorizationMask = $this->graph->getAuthorizationMask($this->object);
        $objectState       = $this->graph->getState($this->readProperty());

        $states = array();
        foreach ($this->graph->getStates() as $key => $state) {
            if ($objectState->isAllow($state, $this->graph, $authorizationMask)) {
                $states[] = $key;
            }
        }

        return $states;
    }

    /**
     * Update state of an object using state mapping
     *
     * @throws Exception\ConflictException if state is already setted on object.
     * @throws Exception\WorkflowException if user try to set an unauthorized state.
     *
     * @param string $state state
     *
     * @return object
     */
    public function updateState($state)
    {
        $actualState = $this->readProperty();

        if ($actualState === $state) {
            throw new Exception\ConflictException(sprintf('State already setted to %s', $state));
        }

        if (!in_array($state, $this->getAuthorizedStates())) {
            throw new Exception\WorkflowException(sprintf('Object cannot pass from state %s to %s', $actualState, $state));
        }

        $this->writeProperty($state);

        return $this->object;
    }

    /**
     * @return string
     */
    protected function readProperty()
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($this->object, $this->property);
    }

    /**
     * @param string $value value
     */
    protected function writeProperty($value)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->setValue($this->object, $this->property, $value);
    }
}
