<?php

$dir = realpath(__DIR__ . '/../view');

return array(
    'router' => array(
        'routes' => array(
            'control' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/ctrl[/]',
                    'defaults' => array(
                        'controller' => 'CoreControl\Controller\Index',
                        'action'     => 'index'
                    )
                )
            ),
//            'control-section' => array(
//                'type' => 'segment',
//                'options' => array(
//                    'route' => '/ctrl/section[/][:action[/]][/:id[/]]',
//                    'constraints' => array(
//                        'action' => 'add|edit|srt',
//                        'id' => '[0-9]+'
//                    ),
//                    'defaults' => array(
//                        'controller' => 'CoreControl\Controller\Section',
//                        'action' => 'index'
//                    )
//                )
//            ),
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'CoreControl\Controller\Index' =>
                'CoreControl\Controller\IndexController'
        )
    ),

    'view_manager' => array(
        'template_path_stack' => array($dir),

        'template_map' => array(
            'core-control/layout' => $dir . '/core-control/layout/layout.phtml',
        )
    ),

    'service_manager' => array(
        'factories' => array(
            'control-navigation' => 'CoreControl\Navigation\NavigationFactory',
        )
    ),

    'navigation' => array(
        'control' => array(
            array(
                'label' => 'Управление',
                'route' => 'control'
            ),
//            array(
//                'label' => 'Какой-то раздел',
//                'route' => 'control-section',
//                'pages' => array(
//                    array(
//                        'label' => 'Пункт 1',
//                        'route' => 'control-section-1'
//                    ),
//                    array(
//                        'label' => 'Пункт 2',
//                        'route' => 'control-section-2'
//                    )
//                )
//            ),
        )
    )
);
