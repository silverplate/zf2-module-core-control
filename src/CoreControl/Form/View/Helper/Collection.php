<?php

namespace CoreControl\Form\View\Helper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormCollection;
use CoreControl\Form\Element\Buttons;

class Collection extends FormCollection
{
    protected static $_renderedFieldsetAmount = 0;

    public function render(ElementInterface $element)
    {
        if (!$element instanceof Buttons) {
            $this->setShouldWrap(false);

            self::$_renderedFieldsetAmount++;

            if (!empty($_COOKIE['cms-open-tab'])) {
                $isActive = $_COOKIE['cms-open-tab'] == $element->getName();
            } else {
                $isActive = self::$_renderedFieldsetAmount == 1;
            }

            return sprintf(
                '<div class="tab-pane fade%s" id="tab-%s">%s</div>',
                $isActive ? ' active in' : '',
                $element->getName(),
                parent::render($element)
            );
        }


        // Collection of buttons

        $delete = $element->getName() . '[delete]';
        foreach ($element->getElements() as $sbt) {
            if ($sbt->getName() == $delete) {
                $sbt->setAttribute('class', 'btn btn-danger pull-right');
                $sbt->setAttribute('onclick', 'return confirm("Вы уверены?");');
                $sbt->setValue($sbt->getValue() . '…');

            } else {
                $sbt->setAttribute('class', 'btn btn-default');
            }
        }

        return '<footer>' . parent::render($element) . '</footer>';
    }
}
