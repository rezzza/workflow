<?php

namespace Rezzza\Workflow\tests\units\State;

use Rezzza\Workflow\Graph;
use Rezzza\Workflow\State\PreviousAll as TestedClass;
use Rezzza\Workflow\Workflow;
use Rezzza\Workflow\tests\fixtures\Item;
use mageekguy\atoum;

class PreviousAll extends atoum\test
{
    public function dataProviderIsAllow()
    {
        return array(
            // no specification
            array(array(1, 1, 1), 'state1', array()),
            array(array(1, 1, 1), 'state2', array('state1')),
            array(array(1, 1, 1), 'state3', array('state1', 'state2')),
            // specification
            array(array(1, 0, 1), 'state1', array()),
            array(array(1, 0, 1), 'state2', array('state1')),
            array(array(1, 0, 1), 'state3', array('state1')),

        );
    }

    /**
     * @dataProvider dataProviderIsAllow
     */
    public function testIsAllow(array $states, $defaultState, array $authorizedStates)
    {
        $graph = new Graph();
        foreach ($states as $i => $state) {
            $spec  = "spec".$i;
            $$spec = new \mock\Rezzza\Workflow\Specification\SpecificationInterface;
            $$spec->getMockController()->isSatisfiedBy = (bool) $state;

            $graph->addState(sprintf('state%s', ($i + 1)), new TestedClass($$spec));
        }

        $data = new \stdClass();
        $data->state = $defaultState;

        $workflow = new Workflow($graph, $data, 'state');

        $this->array($workflow->getAuthorizedStates())
            ->isIdenticalTo($authorizedStates);
    }
}
