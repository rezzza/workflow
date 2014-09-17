Rezzza\Workflow
===============

[![Build Status](https://travis-ci.org/rezzza/workflow.svg?branch=master)](https://travis-ci.org/rezzza/workflow)

Define workflow of an object easily.


Installation
------------

Through Composer :

```
$ composer require --dev "rezzza/workflow:1.0.*@dev"
```


Warning, this library uses binary system, it assigns a binary mask to each state. 
By this way, you'll be limited in number of states.


Usage
-----

```php

use \Rezzza\Workflow\Graph;
use \Rezzza\Workflow\State;
use \Rezzza\Workflow\Workflow;
use \Rezzza\Workflow\Exception;

$graph = new Graph();
$graph
    ->addState('empty', new State\NextOne())                                     // can go to filled
    ->addState('filled', new State\StateCollection(array('empty', 'confirmed'))) // can go to empty or confirmed
    ->addState('confirmed', new State\NextOne())                                 // can go to pending transaction
    ->addState('pending_transaction', new State\NextAll())                       // can go to failing_transaction or success_transaction
    ->addState('failing_transaction', new State\End())
    ->addState('success_transaction', new State\End())

$cart     = new Acme\ECommerce\Path\To\Cart();
$workflow = new Workflow($graph, $cart, 'status');
$states   = $workflow->getAuthorizedStates();

try {
    $workflow->moveToState('filled');
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
    ->addState('confirmed', new State\NextOne(new ConfirmableSpecification()))
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
