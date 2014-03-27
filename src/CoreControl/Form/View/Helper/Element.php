<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormElement;

class Element extends FormElement
{
    public function render(ElementInterface $element)
    {
        $markup = parent::render($element);

        if ($element->getOption('description')) {
            $markup .= '<span class="help-block">' .
                       $element->getOption('description') .
                       '</span>';
        }

        return $markup;
    }
}
