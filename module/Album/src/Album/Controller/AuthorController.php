<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Author;
use Album\Form\AuthorForm;

class AuthorController extends AbstractActionController
{
    protected $authorTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'authors' => $this->getAuthorTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new AuthorForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $author = new Author();
            $form->setInputFilter($author->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $author->exchangeArray($form->getData());
                $this->getAuthorTable()->saveAuthor($author);

                // Redirect to list of authors
                return $this->redirect()->toRoute('author');
            }
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('author', array(
                'action' => 'add'
            ));
        }

        // Get the Author with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $author = $this->getAuthorTable()->getAuthor($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('author', array(
                'action' => 'index'
            ));
        }

        $form  = new AuthorForm();
        $form->bind($author);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($author->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getAuthorTable()->saveAuthor($author);

                // Redirect to list of authors
                return $this->redirect()->toRoute('author');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    // Add content to the following method:
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('author');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAuthorTable()->deleteAuthor($id);
            }

            // Redirect to list of authors
            return $this->redirect()->toRoute('author');
        }

        return array(
            'id'    => $id,
            'author' => $this->getAuthorTable()->getAuthor($id)
        );
    }

    public function getAuthorTable()
    {
        if (!$this->authorTable) {
            $sm = $this->getServiceLocator();
            $this->authorTable = $sm->get('Album\Model\AuthorTable');
        }
        return $this->authorTable;
    }
}
