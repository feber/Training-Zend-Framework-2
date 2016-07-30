<?php

namespace Blog\Auth;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\EventManager\EventManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Blog\Model\Example;
use Blog\Auth\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Db\Adapter\Adapter as DbAdapter;

class Adapter implements AdapterInterface
{
    /**
    * Sets username and password for authentication
    *
    * @return void
    */
    public function __construct($dbAdapter)
    {
        $authAdapter = new AuthAdapter($dbAdapter);
        $authAdapter->setTableName('users')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password');
    }

    /**
    * Performs an authentication attempt
    *
    * @return \Zend\Authentication\Result
    * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
    *               If authentication cannot be performed
    */
    public function authenticate()
    {

    }
}
