Rezzza\Workflow
===============

Define workflow of an object easily.

Installation
------------

Through Composer :

        $ composer require --dev "rezzza/workflow:1.0.*@dev"

Usage
-----

```php

use \Rezzza\Workflow\Graph;
use \Rezzza\Workflow\State;
use \Rezzza\Workflow\Workflow;
use \Rezzza\Workflow\Exception;

$graph = new Graph();
$graph
    ->add('empty', new State\NextOne())                                     // can go to filled
    ->add('filled', new State\StateCollection(array('empty', 'confirmed'))) // can go to empty or confirmed
    ->add('confirmed', new State\NextOne())                                 // can go to pending transaction
    ->add('pending_transaction', new State\NextAll())                       // can go to failing_transaction or success_transaction
    ->add('failing_transaction', new State\End())
    ->add('success_transaction', new State\End())

$cart     = new Acme\ECommerce\Path\To\Cart();
$workflow = new Workflow($graph, $cart, 'status');
$states   = $workflow->getAuthorizedStates();

try {
    $workflow->updateState('filled');
} catch (Exception\ConflictException e) {
    // it seems you want to add a state already setted.
} catch (Exception\WorkflowException $e) {
    // you try to set a state not authorized.
}

```

Specifications
--------------

You can add a specification to each states

```php
use Rezzza\Workflow\Specification\SpecificationInterface;

class ConfirmableSpecification implements SpecificationInterface
{
    public function isSatisfiedBy($object)
    {
        return $object->hasUserAuthenticated();
    }
}

$graph = new Graph();
$graph
    //........
    ->add('confirmed', new State\NextOne(new ConfirmableSpecification()))
    //.........

```

States
------

You can use each one of theses states:

| Name            | Description                                        |
|-----------------|----------------------------------------------------|
| All             | all states                                         |
| End             | no state                                           |
| NextAll         | all states registered after current                |
| NextOne         | next one state registered after current            |
| NextUntil       | all next states registered until defined state     |
| PreviousAll     | all states registered before current               |
| PreviousOne     | previous one state registered before current       |
| PreviousUntil   | all previous states registered until defined state |
| StateCollection | states defined via a collection of id              |
