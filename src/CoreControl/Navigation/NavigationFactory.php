<?php

namespace CoreControl\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;

class NavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'control';
    }
}
