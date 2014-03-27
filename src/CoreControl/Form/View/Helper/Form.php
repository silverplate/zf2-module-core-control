<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\FormInterface;
use CoreControl\Form\Element\Buttons;

class Form extends \Zend\Form\View\Helper\Form
{
    public function render(FormInterface $_form)
    {
        $markup = array();
        foreach ($_form->getFieldsets() as $fieldset) {
            if (!$fieldset instanceof Buttons) {
                $isActive = empty($_COOKIE['cms-open-tab'])
                          ? count($markup) == 0
                          : $_COOKIE['cms-open-tab'] == $fieldset->getName();

                $markup[] = sprintf(
                    '<li%s><a href="#tab-%s" data-toggle="tab">%s</a></li>',
                    $isActive ? ' class="active"' : '',
                    $fieldset->getName(),
                    $fieldset->getLabel()
                );
            }
        }

        $formMarkup = parent::render($_form);

        if (count($markup) > 0) {
            $formMarkup = '<ul class="nav nav-tabs">' . implode($markup) .
                          '</ul>' . $formMarkup;
        }

        return $formMarkup;
    }

    public function openTag(FormInterface $_form = null)
    {
        return parent::openTag($_form) . '<div class="tab-content">';
    }

    public function closeTag()
    {
        return '</div>' . parent::closeTag();
    }
}
