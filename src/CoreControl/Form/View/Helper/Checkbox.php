<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\View\Helper\FormCheckbox;
use Zend\Form\ElementInterface;

class Checkbox extends FormCheckbox
{
    public function render(ElementInterface $element)
    {
        return '<div class="checkbox"><label>' .
               parent::render($element) .
               ' да</label></div>';
    }
}
