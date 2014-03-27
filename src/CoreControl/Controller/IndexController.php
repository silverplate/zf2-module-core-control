<?php

namespace CoreControl\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $this->_setPageTitle('Управление');

        $data = array('content' =>
            'При верстке текстов желательно пользоваться правилами ' .
            '<a href="http://sitedev.ru/services/typo/">экранной ' .
            'типографики</a>.'
        );

        $view = new ViewModel($data);
        $view->setTemplate('core-control/index/index');

        return $view;
    }
}
