<?php
// Filename: /module/Blog/src/Blog/Factory/PostControllerFactory.php
namespace Blog\Factory;

use Blog\Controller\AuthController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthControllerFactory implements FactoryInterface
{
    /**
    * Create service
    *
    * @param ServiceLocatorInterface $serviceLocator
    *
    * @return mixed
    */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sl = $serviceLocator->getServiceLocator();
        return new AuthController(
            $sl->get('config')['db']
        );
    }
}
