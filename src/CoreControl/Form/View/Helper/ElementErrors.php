<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElementErrors;

class ElementErrors extends FormElementErrors
{
    public function render(ElementInterface $element, array $attributes = array())
    {
        $this->setMessageOpenFormat('<span class="help-block">');
        $this->setMessageSeparatorString('</span><span class="help-block">');
        $this->setMessageCloseString('</span>');

        return parent::render($element, $attributes);
    }
}
