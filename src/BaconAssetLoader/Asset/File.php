<?php
/**
 * BaconAssetLoader
 *
 * @link      http://github.com/Bacon/BaconAssetLoader For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconAssetLoader\Asset;

/**
 * Representation of a single file.
 */
class File
{
    /**
     * Path to the file.
     *
     * @var string
     */
    protected $path;

    /**
     * Filters to be applied to the file.
     *
     * @var array
     */
    protected $filters = array();

    /**
     * Create a new file representation.
     *
     * @param  string $path
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Get the path to the file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Add a filter to the file.
     *
     * @param  array $filter
     * @return void
     */
    public function addFilter(array $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Stream the file contents to the client.
     *
     * @return void
     */
    public function streamToClient()
    {
        $filters = array();

        if ($this->filters) {
            foreach ($this->filters as $filter) {
                if ($filter['on_the_fly']) {
                    $filters[] = $filter['filter'];
                }
            }
        }

        if ($filters) {
            $contents = file_get_contents($this->path);

            foreach ($filters as $filter) {
                $contents = $filter->filter($contents);
            }

            echo $contents;
        } else {
            readfile($this->path);
        }
    }
}
