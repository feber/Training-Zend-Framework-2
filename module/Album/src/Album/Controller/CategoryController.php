<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Category;
use Album\Form\CategoryForm;

class CategoryController extends AbstractActionController
{
    protected $categoryTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'categories' => $this->getCategoryTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new CategoryForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category->exchangeArray($form->getData());
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of categorys
                return $this->redirect()->toRoute('category');
            }
        }
        return array('form' => $form);
    }

    // Add content to this method:
    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category', array(
                'action' => 'add'
            ));
        }

        // Get the Category with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            $category = $this->getCategoryTable()->getCategory($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('category', array(
                'action' => 'index'
            ));
        }

        $form  = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of categorys
                return $this->redirect()->toRoute('category');
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
            return $this->redirect()->toRoute('category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCategoryTable()->deleteCategory($id);
            }

            // Redirect to list of categorys
            return $this->redirect()->toRoute('category');
        }

        return array(
            'id'    => $id,
            'category' => $this->getCategoryTable()->getCategory($id)
        );
    }

    public function getCategoryTable()
    {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Album\Model\CategoryTable');
        }
        return $this->categoryTable;
    }
}
