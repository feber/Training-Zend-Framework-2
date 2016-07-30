<?php
namespace Blog\Controller;

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

class AuthController extends AbstractActionController
{

    protected $authAdapter;
    protected $auth;
    protected $dbAdapter;

    public function __construct(array $config)
    {
        $this->dbAdapter = new DbAdapter($config);
        $this->authAdapter = new AuthAdapter($this->dbAdapter, 'users', 'username', 'password');
        $this->auth = new AuthenticationService();
    }

    public function indexAction() {
        return $this->redirect()->toUrl('auth/login');
    }

    public function loginAction()
    {
        $stream = @fopen('data/log.txt', 'a', false);
        if (! $stream) {
            throw new Exception('Failed to open stream');
        }

        // $writer = new \Zend\Log\Writer\Stream($stream);
        $mapping = array(
            'timestamp' => 'timestamp',
            'priority'  => 'priority',
            'message'   => 'message'
        );
        $writer = new \Zend\Log\Writer\Db($this->dbAdapter, 'log', $mapping);
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $logger->log(\Zend\Log\Logger::INFO, 'Ada yang mau login');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $username = $request->getPost()['username'];
            $password = $request->getPost()['password'];

            $this->authAdapter->setIdentity($username)->setCredential($password);
            $result = $this->auth->authenticate($this->authAdapter);

            if ($result->isValid()) {
                $logger->log(\Zend\Log\Logger::INFO, 'Valid wee');
            } else {
                $logger->log(\Zend\Log\Logger::WARN, 'Ada yang gagal login');
            }

            switch ($result->getCode()) {
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                echo 'Result::FAILURE_IDENTITY_NOT_FOUND';
                break;

                case Result::FAILURE_CREDENTIAL_INVALID:
                echo 'Result::FAILURE_CREDENTIAL_INVALID';
                break;

                case Result::SUCCESS:
                echo 'Result::SUCCESS';
                break;

                default:
                echo 'Result::NAH_LOH';
                break;
            }
        }

        return new ViewModel([
            'username' => $this->auth->getStorage()->read()
        ]);
    }

    public function logoutAction()
    {
        $this->auth->clearIdentity();

        return $this->redirect()->toRoute('auth');
    }
}
