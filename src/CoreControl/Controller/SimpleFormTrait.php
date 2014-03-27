<?php

namespace CoreControl\Controller;

use CoreControl\Form\AbstractForm;

trait SimpleFormTrait
{
    protected $_entity;

    /** @var AbstractForm */
    protected $_form;

    /**
     * @return object
     */
    protected function _getEntity()
    {
        if (is_null($this->_entity)) {
            $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

            if ($id) {
                $this->_entity = $this->mpr()->findById($id);
            }

            if (!$this->_entity) {
                $this->_entity = $this->mpr()->getEntityPrototype();
            }
        }

        return $this->_entity;
    }

    public function ent()
    {
        return $this->_getEntity();
    }

    /**
     * @return \CoreApplication\Mapper\AbstractMapper
     * @throws \Exception
     */
    protected function _getMapper()
    {
        throw new \Exception('Method ' . __METHOD__ . ' is not implemented');
    }

    public function mpr()
    {
        return $this->_getMapper();
    }

    /**
     * @return \Zend\Form\Form
     * @throws \Exception
     */
    protected function _createForm()
    {
        throw new \Exception('Method ' . __METHOD__ . ' is not implemented');
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $this->_getView()->setTemplate('core-control/layout/list');
        $this->_getView()->setVariable('list', $this->mpr()->getList());

        if ($this->mpr()->isSortable()) {
            $this->_getView()->setVariable('isSortable', true);
        }

        return $this->_getView();
    }

    public function addAction()
    {
        $form = $this->_form();

        if ($form) {
            $this->_setPageTitle('Добавление');

            $this->_addBreadcrumb(rtrim($this->url()->fromRoute(
                $this->_route,
                array('action' => 'add')
            ), '/'), 'Добавление');

            $this->_getView()->setTemplate('core-control/forms/form');
            $this->_getView()->setVariable('form', $form);

            return $this->_getView();
        }

        return $form;
    }

    public function editAction()
    {
        $form = $this->_form();

        if ($form) {
            if (!$this->_getEntity()->getId()) {
                return $this->notFoundAction();
            }

            if (method_exists($this->_getEntity(), 'toTitle')) {
                $this->_setPageTitle($this->_getEntity()->toTitle());

            } else {
                $this->_setPageTitle($this->_getEntity()->getTitle());
            }

            $this->_addBreadcrumb(rtrim($this->url()->fromRoute(
                $this->_route,
                array('action' => 'edit', 'id' => $this->_getEntity()->getId())
            ), '/'), 'Редактирование');

            $this->_getView()->setTemplate('core-control/forms/form');
            $this->_getView()->setVariable('form', $form);

            return $this->_getView();
        }

        return $form;
    }

    protected function _delete()
    {
        if ($this->ent()->getId()) {
            $isError = false;

            try {
                $this->mpr()->deleteEntity($this->ent());

            } catch (\Exception $e) {
                $isError = true;
                if ($this->_isErrorOutput()) throw $e;
            }

            if (!$isError) {
                $this->flashMessenger()->addSuccessMessage('Запись удалена.');
            }
        }

        $this->redirect()->toRoute($this->_route);
        return array();
    }

    protected function _isErrorOutput()
    {
        $config = $this->srv('Config');
        return !empty($config['env']) && $config['env'] == 'development';
    }

    protected function _getForm()
    {
        if ($this->_form === null) {
            $this->_form = $this->_createForm();
        }

        return $this->_form;
    }

    protected function _save()
    {
        $this->_getForm()->setData($this->getRequest()->getPost());
        $action = $this->getEvent()->getRouteMatch()->getParam('action');
        $isError = !$this->_getForm()->isValid();

        if (!$isError) {
            try {
                if ($action == 'add') {
                    $this->mpr()->insertEntity($this->ent());

                    if (
                        $this->mpr()->isSortable() &&
                        !$this->ent()->getSortOrder()
                    ) {
                        $this->ent()->setSortOrder($this->ent()->getId());
                        $this->mpr()->updateEntity($this->ent());
                    }

                } else {
                    $this->mpr()->updateEntity($this->ent());
                }

            } catch (\Exception $e) {
                $isError = true;
                if ($this->_isErrorOutput()) throw $e;
            }

            if (!$isError) {
                $this->flashMessenger()->addSuccessMessage('Данные сохранены.');

                $this->redirect()->toRoute($this->_route, array(
                    'action' => 'edit',
                    'id' => $this->ent()->getId()
                ));

                return array();
            }
        }

        if ($isError) {
            $this->flashMessenger()->addErrorMessage('Произошла ошибка.');
        }

        return $this->_getForm();
    }

    protected function _form()
    {
        if ($this->getRequest()->isPost()) {
            $postButtons = $this->getRequest()->getPost('buttons', array());

            if (!empty($postButtons['delete']))    return $this->_delete();
            else if (!empty($postButtons['save'])) return $this->_save();
        }

        return $this->_getForm();
    }
}
