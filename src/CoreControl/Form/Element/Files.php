<?php

namespace CoreControl\Form\Element;

use Zend\Form\Element\MultiCheckbox;
use Zend\Form\FormInterface;
use Zend\Form\ElementPrepareAwareInterface;

class Files extends MultiCheckbox implements ElementPrepareAwareInterface
{
    use ElementsTrait;

    public function prepareElement(FormInterface $_form)
    {
        $_form->setAttribute('enctype', 'multipart/form-data');
    }
}
