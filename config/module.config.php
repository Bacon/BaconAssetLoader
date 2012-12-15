<?php
return array(
    'console' => array(
        'router' => array(
            'routes' => array(
                'baconassetloader-publish-assets' => array(
                    'options' => array(
                        'controller' => 'BaconAssetLoader.Controller.Publish',
                        'action'     => 'publish'
                    )
                )
            )
        )
    ),
    'controllers' => array(
        'factories' => array(
            'BaconAssetLoader.Controller.Publish' => function ($sm) {
                return new BaconAssetLoader\Controller\PublishController(
                    $sm->getServiceLocator()->get('BaconAssetLoader.AssetManager')
                );
            },
        ),
    ),
);
