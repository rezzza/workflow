<?php

namespace Rezzza\Workflow;

/**
 * Graph
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Graph
{
    /**
     * @var array
     */
    private $states = array();

    /**
     * @var integer
     */
    private $maskIterator;

    /**
     * @param string               $key   key
     * @param State\StateInterface $state state
     *
     * @return self
     */
    public function addState($key, State\StateInterface $state = null)
    {
        if (null === $state) {
            $state = new State\All();
        }

        $this->states[$key] = $state;

        $this->maskIterator = ($this->maskIterator === null) ? 1 : $this->maskIterator << 1;

        if ($this->maskIterator === 0) {
            throw new \OutOfBoundsException(sprintf('You added too many states, Rezzza\Workflow uses bits and assign them to states.'));
        }

        $state->setMask($this->maskIterator);

        return $this;
    }

    /**
     * @return array<State\StateInterface>
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @return integer
     */
    public function getAuthorizationMask($object)
    {
        $mask = 0;
        foreach ($this->getStates() as $state) {
            if ($state->isAuthorized($object)) {
                $mask |= $state->getMask();
            }
        }

        return $mask;
    }

    /**
     * @param string $state state
     *
     * @return State\StateInterface
     */
    public function getState($key)
    {
        if (!isset($this->states[$key])) {
            throw new \InvalidArgumentException(sprintf('State "%s" does not exist', $key));
        }

        return $this->states[$key];
    }
}
