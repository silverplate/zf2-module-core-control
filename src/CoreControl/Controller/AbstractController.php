<?php

namespace CoreControl\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Navigation;
use Zend\View\Model\ViewModel;

abstract class AbstractController extends AbstractActionController
{
    protected $_view;
    protected $_breadcrumbs;
    protected $_title;
    protected $_route;

    protected function _setPageTitle($_title)
    {
        $this->_appendPageTitle($_title);
    }

    protected function _appendPageTitle($_title)
    {
        $this->srv('viewHelperManager')->get('headTitle')->append($_title);
    }

    public function srv($_name)
    {
        return $this->getServiceLocator()->get($_name);
    }

    protected function _getView()
    {
        if (!isset($this->_view)) {
            $this->_view = new ViewModel();
        }

        return $this->_view;
    }

    /**
     * @return Navigation\Navigation
     */
    protected function _getBreadcrumbs()
    {
        if (is_null($this->_breadcrumbs)) {
            $this->_breadcrumbs = new Navigation\Navigation;
        }

        return $this->_breadcrumbs;
    }

    protected function _addBreadcrumb($_uri, $_label)
    {
        $this->_getBreadcrumbs()->addPage(new Navigation\Page\Uri(array(
            'uri' => $_uri,
            'label' => $_label
        )));
    }

    public function onDispatch(MvcEvent $_e)
    {
        /**
         * @todo Сложно переопределить, реализовать иначе
         * $this->layout()->setTemplate('core-control/layout/layout');
         */

        $result = parent::onDispatch($_e);


        // Вычисление выбранного пункта навигации

        $this->_updateNavBranch($this->srv('control-navigation')->getPages());


        if ($result instanceof ViewModel) {

            // Заголовок

            $this->srv('viewHelperManager')->get('headTitle')->prepend(
                $this->_title
            );


            // Путь к разделу

            $path = rtrim($this->url()->fromRoute($this->_route), '/');
            $result->setVariable('path', $path);


            // Хлебные крошки

            $this->_getBreadcrumbs()->setPages(array_merge(
                array(new Navigation\Page\Uri(array(
                    'uri' => $path,
                    'label' => $this->_title
                ))),
                $this->_getBreadcrumbs()->getPages()
            ));

            $result->setVariable('breadcrumbs', $this->_getBreadcrumbs());
        }

        return $result;
    }

    /**
     * @param Navigation\Page\Mvc[] $_pages
     * @return null|bool
     */
    protected function _updateNavBranch(array $_pages)
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();
        $uri = rtrim($request->getUri()->getPath(), '/');
        $homeUri = rtrim($this->url()->fromRoute('control'), '/');
        $homePage = null;
        $activePage = null;
        $activeSubPage = null;

        foreach ($_pages as $page) {
            $href = rtrim($page->getHref(), '/');
            if ($href == $homeUri) $homePage = $page;

            $isSel = $uri == $href;
            $isActive = $homePage != $page &&
                        ($isSel || strpos($uri, $href) === 0);

            $page->setActive($isActive);
            if ($isActive) $activePage = $page;

            if ($isSel) {
                $page->setParams(array_merge(
                    $page->getParams(),
                    array('is-selected' => true)
                ));
            }

            if ($page->getPages()) {
                $activeSubPage = $this->_updateNavBranch($page->getPages());

                if (!$activePage && $activeSubPage) {
                    $activePage = $page;
                    $page->setActive(true);
                }
            }
        }

        if ($homePage) {
            if (
                !$activeSubPage &&
                !$activePage &&
                strpos($uri, $homeUri) === 0
            ) {
                $homePage->setActive(true);
            }

            return null;

        } else {
            return $activePage !== null || $activeSubPage !== null;
        }
    }
}
