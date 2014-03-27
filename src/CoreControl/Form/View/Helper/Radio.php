<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\View\Helper\FormRadio;
use Zend\Form\ElementInterface;

class Radio extends FormRadio
{
    public function render(ElementInterface $element)
    {
//        $attrs = $element->getLabelAttributes();
//        if ($attrs && array_key_exists('class', $attrs)) {
//            unset($attrs['class']);
//            $element->setLabelAttributes($attrs);
//        }

        $class = 'radio';
        if (count($element->getValueOptions()) < 6) $class .= '-inline';
        $openTag = '<div class="' . $class . '">';
        $closeTag = '</div>';

        $this->setSeparator($closeTag . $openTag);

        return $openTag . parent::render($element) . $closeTag;
    }
}
