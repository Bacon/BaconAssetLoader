<?php
/**
 * BaconAssetLoader
 *
 * @link      http://github.com/Bacon/BaconAssetLoader For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconAssetLoader;

use BaconAssetLoader\Asset\Manager as AssetManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

/**
 * Module for loading assets in development and compiling for production.
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * Called when all modules are loaded.
     *
     * @return void
     */
    public function onBootstrap(MvcEvent $e)
    {
    	$service = $e->getApplication()->getServiceManager()->get('BaconAssetLoader.AssetManager');
        $service->collectAssetInformation();
    }

    /**
     * getConfig(): defined by ConfigProviderInterface.
     *
     * @see    ConfigProviderInterface::getConfig()
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * getAutoloaderConfig(): defined by AutoloaderProviderInterface.
     *
     * @see    AutoloaderProviderInterface::getAutoloaderConfig()
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

    /**
     * getServiceConfig(): defined by ServiceProviderInterface.
     *
     * @see    ServiceProviderInterface::getServiceConfig()
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'BaconAssetLoader.AssetManager' => function ($sm) {
                    $service = new AssetManager($sm->get('EventManager'));
                    return $service;
                },
            ),
        );
    }
}
