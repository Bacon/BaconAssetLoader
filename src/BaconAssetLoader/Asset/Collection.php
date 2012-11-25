<?php
/**
 * BaconAssetLoader
 *
 * @link      http://github.com/Bacon/BaconAssetLoader For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconAssetLoader\Asset;

use Zend\Filter\Filter;
use Zend\Stdlib\SplPriorityQueue;

/**
 * Asset collection.
 */
class Collection
{
    /**
     * Path to assets.
     *
     * @var string
     */
    protected $path;

    /**
     * Filters for specific files.
     *
     * @var SplPriorityQueue
     */
    protected $filters;

    /**
     * Create a new asset collection.
     *
     * @param  string $path
     * @return void
     */
    public function __construct($path)
    {
        $this->path    = rtrim($path, '/');
        $this->filters = new SplPriorityQueue();
    }

    /**
     * Add a filter to the collection.
     *
     * @param  string  $pattern
     * @param  Filter  $filter
     * @param  integer $priority
     * @param  boolean $onTheFly
     * @param  boolean $onCompile
     * @return Collection
     */
    public function addFilter(
        $pattern,
        Filter $filter,
        $priority = 1,
        $onTheFly = true,
        $onCompile = true
    ) {
        $this->filters->insert(array(
            'pattern'    => $pattern,
            'filter'     => $filter,
            'on_the_fly' => $onTheFly,
            'on_compile' => $onCompile
        ), $priority);

        return $this;
    }

    /**
     * Try to get a specific asset.
     *
     * @param  string $path
     * @return File|null
     */
    public function getAsset($path)
    {
        if (is_file($this->path . $path)) {
            $file = new File($this->path . $path);

            foreach ($this->filters as $filter) {
                if (fnmatch($path, $filter['pattern'])) {
                    $file->addFilter($filter);
                }
            }

            return $file;
        }

        return null;
    }
}
