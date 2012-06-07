AssetLoader module for ZF2
==========================

Introduction
------------
BaconAssetLoader is a module for ZF2 to ease the loading of assets within
development from multiple modules. This is not meant for production, as it adds
quite a bit of overhead. For deployment this module supplies a CLI script to
compile all assets into the public directory of an application.

Usage
-----
To use the module, add it to your application config. After that you must add
a listener in the "Module.php" of those modules which contain public assets. A
very basic example, assuming your asset directory is called "public", looks like
this:

    use BaconAssetLoader\Asset\Collection as AssetCollection;

    …

    public function onBootstrap(\Zend\Mvc\MvcEvent $mvcEvent)
    {
        /**
         * @var \Zend\EventManager\SharedEventManager
         */
        $sharedEvents = $mvcEvent->getApplication()->events()->getSharedManager();
        $sharedEvents->attach('BaconAssetLoader\Asset\Manager', 'collectAssetInformation', function(\Zend\EventManager\Event $event) {
            $event->getTarget()->addAssets(new AssetCollection(__DIR__ . '/public'));
        });
    }

