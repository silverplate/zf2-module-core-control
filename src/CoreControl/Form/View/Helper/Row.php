<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\View\Helper\FormRow;
use Zend\Form\ElementInterface;
use Zend\Form\Element\Checkbox;

class Row extends FormRow
{
    public function render(ElementInterface $element)
    {
        if (!$element->getLabel()) return parent::render($element);


        // Add id attribute to prevent element wrapping by label tag

        if (!$element->hasAttribute('id')) {
//            $id = str_replace(
//                array('[', ']'),
//                array('_', ''), $element->getName()
//            );
//
//            $element->setAttribute('id', $id);
            $element->setAttribute('id', $element->getName());
        }


        // Required mark

        if ($element->getOption('is_required')) {
            $element->setLabel(
                $element->getLabel() .
                '<span class="required">&acute;</span>'
            );

        } else  if ($element->getOption('is_optional_required')) {
            $element->setLabel(
                $element->getLabel() .
                '<span class="optional-required">&acute;</span>'
            );
        }


        // Add label classes

        $attrs = $element->getLabelAttributes();
        if (!$attrs) $attrs = array();
        if (!array_key_exists('class', $attrs)) $attrs['class'] = '';
        $originAttrs = $attrs;
        $attrs['class'] = trim($attrs['class'] . ' col-sm-3 control-label');
        $element->setLabelAttributes($attrs);

        $label = $this->getLabelHelper()->__invoke($element);
        // Restore attributes for sub-labels
        $element->setLabelAttributes($originAttrs);


        // Add form-control class for regular inputs

        if (!$element instanceof Checkbox) {
            $class = $element->getAttribute('class') ?: '';
            if (!$class || strpos($class, 'form-control') === false) {
                $class .= trim(' form-control');
                $element->setAttribute('class', $class);
            }
        }


        // Class if error

        $class = array('form-group');
        if ($element->getMessages()) $class[] = 'has-error';

        return sprintf(
            '<div class="%s">%s<div class="col-sm-9">%s</div></div>',
            implode(' ', $class),
            $label,
            $this->getElementHelper()->render($element) .
            $this->getElementErrorsHelper()->render($element)
        );
    }
}
