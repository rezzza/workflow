<?php

namespace Rezzza\Workflow\tests\units\State;

use Rezzza\Workflow\Graph;
use Rezzza\Workflow\State\StateCollection as TestedClass;
use Rezzza\Workflow\Workflow;
use Rezzza\Workflow\tests\fixtures\Item;
use mageekguy\atoum;

class StateCollection extends atoum\test
{
    public function dataProviderIsAllow()
    {
        return array(
            // no specification (default) (previous until) (results)
            array(array(1, 1, 1), 'state1', array('state1'), array()),
            array(array(1, 1, 1), 'state1', array('state1', 'state2'), array('state2')),
            array(array(1, 1, 1), 'state1', array('state1', 'state2', 'state3'), array('state2', 'state3')),
            // specification (default) (previous until) (results)
            array(array(1, 0, 1), 'state1', array('state1'), array()),
            array(array(1, 0, 1), 'state1', array('state1', 'state2'), array()),
            array(array(1, 0, 1), 'state1', array('state1', 'state2', 'state3'), array('state3')),


        );
    }

    /**
     * @dataProvider dataProviderIsAllow
     */
    public function testIsAllow(array $states, $defaultState, $stateCollection, array $authorizedStates)
    {
        $graph = new Graph();
        foreach ($states as $i => $state) {
            $spec  = "spec".$i;
            $$spec = new \mock\Rezzza\Workflow\Specification\SpecificationInterface;
            $$spec->getMockController()->isSatisfiedBy = (bool) $state;

            $graph->addState(sprintf('state%s', ($i + 1)), new TestedClass($stateCollection, $$spec));
        }

        $data = new \stdClass();
        $data->state = $defaultState;

        $workflow = new Workflow($graph, $data, 'state');

        $this->array($workflow->getAuthorizedStates())
            ->isIdenticalTo($authorizedStates);
    }
}
