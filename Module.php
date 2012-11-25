<?php

namespace BaconAssetLoader;

use BaconAssetLoader\Asset\Manager as AssetManager,
    Zend\Mvc\MvcEvent;

/**
 * Module for loading assets in development and compiling for production.
 */
class Module
{

    /**
     * Called when all modules are loaded.
     *
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
        /**
         * @var AssetManager;
         */
    	$service = $e->getApplication()->getServiceManager()->get('BaconAssetLoader.AssetManager');
        $service->collectAssetInformation();
    }

    /**
     * Get autoloader config.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
            ),
            'factories' => array(
                'BaconAssetLoader.AssetManager' => function ($sm) {
                    $service = new AssetManager($sm->get('EventManager'));

                    return $service;
                },
            ),
        );
    }

}
