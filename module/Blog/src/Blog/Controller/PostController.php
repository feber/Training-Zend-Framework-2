<?php
// Filename: /module/Blog/src/Blog/Controller/PostController.php
namespace Blog\Controller;

use Blog\Service\PostServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PostController extends AbstractActionController
{
    /**
    * @var \Blog\Service\PostServiceInterface
    */
    protected $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'posts' => $this->postService->findAllPosts()
        ));
    }
}
