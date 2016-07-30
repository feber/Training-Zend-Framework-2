<?php

namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class AuthorTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAuthor($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAuthor(Author $author)
    {
        $data = array(
            'name'  => $author->name,
        );

        $id = (int) $author->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAuthor($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Author id does not exist');
            }
        }
    }

    public function deleteAuthor($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
