<?php
namespace BaconAssetLoader;

use Zend\Module\Manager as ModuleManager,
    Zend\EventManager\Event,
    Zend\Module\Consumer\AutoloaderProvider,
    BaconAssetLoader\Asset\Manager as AssetManager;

/**
 * Module for loading assets in development and compiling for production.
 */
class Module implements AutoloaderProvider
{
    /**
     * Asset manager.
     * 
     * @var Manager
     */
    protected $assetManager;

    /**
     * Initialize the module.
     *
     * @param  ModuleManager $moduleManager
     * @return void
     */
    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
    }

    /**
     * Called when all modules are loaded.
     * 
     * @return void
     */
    public function modulesLoaded(Event $event) {
        $this->assetManager()->collectAssetInformation();
    }
    
    /**
     * Get the asset manager.
     * 
     * @return AssetManager
     */
    public function assetManager()
    {
        if ($this->assetManager === null) {
            $this->assetManager = new AssetManager();
        }
        
        return $this->assetManager;
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
}
