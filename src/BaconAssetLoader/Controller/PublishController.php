<?php
/**
 * BaconAssetLoader
 *
 * @link      http://github.com/Bacon/BaconAssetLoader For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconAssetLoader\Controller;

use BaconAssetLoader\Asset\Manager as AssetManager;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Publich controller.
 */
class PublishController extends AbstractActionController
{
    /**
     * Asset manager to use for publishing.
     *
     * @var AssetManager
     */
    protected $assetManager;

    /**
     * Create a new publish controller.
     *
     * @param AssetManager $assetManager
     */
    public function __construct(AssetManager $assetManager)
    {
        $this->assetManager = $assetManager;
    }

    /**
     * Publish all assets.
     *
     * @return void
     */
    public function publishAction()
    {
        $request = $this->getRequest();
        $path    = $request->getPath();

        $this->assetManager->publish($path);
    }
}
