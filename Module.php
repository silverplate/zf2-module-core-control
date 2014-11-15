<?php

namespace CoreControl;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'Form' => __NAMESPACE__ . '\Form\View\Helper\Form',
                'FormRow' => __NAMESPACE__ . '\Form\View\Helper\Row',
                'FormElement' => __NAMESPACE__ . '\Form\View\Helper\Element',
                'FormElementErrors' =>
                    __NAMESPACE__ . '\Form\View\Helper\ElementErrors',
                'FormRadio' => __NAMESPACE__ . '\Form\View\Helper\Radio',
                'FormCheckbox' => __NAMESPACE__ . '\Form\View\Helper\Checkbox',
                'FormCollection' =>
                    __NAMESPACE__ . '\Form\View\Helper\Collection',
                'FormMultiCheckbox' =>
                    __NAMESPACE__ . '\Form\View\Helper\MultiCheckbox',
            )
        );
    }
}
