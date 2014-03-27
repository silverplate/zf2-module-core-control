<?php

namespace CoreControl\Form;

use Zend\Form;
use Zend\Form\Element\Submit;
use Zend\Stdlib\Hydrator\ClassMethods;
use CoreControl\Form\FormInterface as CtrlFormInterface;
use CoreControl\Form\Element\Buttons;

abstract class AbstractForm extends Form\Form implements CtrlFormInterface
{
    public function __construct($_name = null, $_object = null)
    {
        parent::__construct($_name);

        if ($this->hydrator === null) {
            $this->setHydrator(new ClassMethods);
        }

        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');

        $this->createElements();
        $this->createButtons();

        if ($_object) $this->bind($_object);
    }

    public function createButtons()
    {
        $buttons = new Buttons('buttons');
        $buttons->setCount(2);
        $this->add($buttons);

        $submit = new Submit('save');
        $submit->setValue('Сохранить');
        $buttons->add($submit);

        $submit = new Submit('delete');
        $submit->setValue('Удалить');
        $buttons->add($submit);
    }

    public function bind($_object, $_fi = Form\FormInterface::VALUES_NORMALIZED)
    {
        $res = parent::bind($_object, $_fi);

        if (
            $res->has('buttons') &&
            $res->get('buttons')->has('delete') &&
            !$_object->getId()
        ) {
            $res->get('buttons')->remove('delete');
        }

        return $res;
    }
}
