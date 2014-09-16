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
     * @var float
     */
    private $maskIterator = 0;

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

        $this->maskIterator = ($this->maskIterator === 0) ? 1 : $this->maskIterator << 1;

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
