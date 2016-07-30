<?php
namespace Blog\Controller;

use Zend\EventManager\EventManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Example;

class EventManagerController extends AbstractActionController
{
    public function indexAction(){
        $example = new Example();

        $example->getEventManager()->attach('doSomething', function($e) {
            $event  = $e->getName();
            $target = get_class($e->getTarget()); // "Example"
            $params = $e->getParams();
            printf(
            'Handled event "%s" on target "%s", with parameters %s',
            $event,
            $target,
            json_encode($params)
        );
    });

    $example->doSomething('bar', 'bat');
    return false;
    }
}
