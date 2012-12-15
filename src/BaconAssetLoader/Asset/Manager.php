<?php
/**
 * BaconAssetLoader
 *
 * @link      http://github.com/Bacon/BaconAssetLoader For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconAssetLoader\Asset;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

/**
 * Asset manager.
 */
class Manager implements EventManagerAwareInterface
{

    /**
     * Registered asset collections.
     *
     * @var array
     */
    protected $assets = array();

    /**
     * Events other modules can subscribe to.
     *
     * @var EventManager
     */
    protected $events;

    /**
     * Constructor
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function __construct(EventManager $eventManager)
    {
        $this->setEventManager($eventManager);
        $this->getEventManager()->getSharedManager()->attach(
            'Zend\Mvc\Application',
            'route',
            array($this, 'testRequestForAsset'),
            PHP_INT_MAX
        );
    }

    /**
     * Trigger event to collect asset information.
     *
     * @return void
     */
    public function collectAssetInformation()
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this);
    }

    /**
     * Add assets to the manager.
     *
     * @param  AssetCollection $assets
     * @return void
     */
    public function addAssets(Collection $assets)
    {
        $this->assets[] = $assets;
    }

    /**
     * Test the request for an existing asset.
     *
     * If an asset matches the request, it is passed to the client and
     * the application quits.
     *
     * @param  MvcEvent $event
     * @return void
     */
    public function testRequestForAsset(MvcEvent $event)
    {
        $request = $event->getRequest();

        if (!method_exists($request, 'getUri')) {
            return;
        }

        if (method_exists($request, 'getBaseUrl')) {
            $baseUrlLength = strlen($request->getBaseUrl() ? : '');
        } else {
            $baseUrlLength = 0;
        }

        $path = substr($request->getUri()->getPath(), $baseUrlLength);

        if (substr($path, -1) == '/') {
            return;
        }

        $file = null;

        foreach ($this->assets as $collection) {
            if (null !== ($file = $collection->getAsset($path))) {
                break;
            }
        }

        if ($file !== null) {
            $mimeType = MimeDetector::getMimeType($file->getPath());

            header('Content-Type: ' . $mimeType);
            $file->streamToClient();
            exit;
        }
    }

    /**
     * Publish all collected assets to a given path.
     *
     * @param  string $path
     * @return void
     */
    public function publish($path)
    {
        foreach ($this->assets as $collection) {
            $collection->publish($path);
        }
    }

    /**
     * Set the event manager instance used by this module manager.
     *
     * @param  EventManagerInterface $events
     * @return Manager
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
        ));
        $this->events = $events;
        return $this;
    }

    /**
     * Get event manager instance.
     *
     * @return EventCollection
     */
    public function getEventManager()
    {
        if ($this->events === null) {
            $this->events = new EventManager(__CLASS__);
        }

        return $this->events;
    }

}
