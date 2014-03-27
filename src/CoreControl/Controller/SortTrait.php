<?php

namespace CoreControl\Controller;

trait SortTrait
{
    /** @var string */
    protected $_sortOrderMethod;

    public function srtAction()
    {
        if (!empty($_POST['sort-items'])) {
            $data = $_POST['sort-items'];
            $newOrder = array();

            foreach ($data as $id) {
                $newOrder[$id] = count($newOrder);
            }

            $currentOrder = array();
            $entities = $this->_getMapper()->fetchAll(array(
                current($this->_getMapper()->getPri()) => $data
            ))->asArray();

            $sortOrderMethod = isset($this->_sortOrderMethod)
                             ? $this->_sortOrderMethod
                             : 'sort';
            uasort(
                $entities,
                array(
                    $this->_getMapper()->getEntityPrototype(),
                    $sortOrderMethod
                )
            );

            foreach ($entities as $entity) {
                $currentOrder[] = $entity->getSortOrder();
            }

            foreach ($entities as $entity) {
                $sortOrder = $currentOrder[$newOrder[$entity->getId()]];

                if ($sortOrder && $sortOrder != $entity->getSortOrder()) {
                    $entity->setSortOrder($sortOrder);
                    $this->_getMapper()->updateEntity($entity);
                }
            }
        }

        return $this->response;
    }
}
