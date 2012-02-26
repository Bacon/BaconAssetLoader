<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'zfcassetmanager' => array(
                            'type'     => 'ZfcAssets\Route',
                            'priority' => PHP_INT_MAX,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
