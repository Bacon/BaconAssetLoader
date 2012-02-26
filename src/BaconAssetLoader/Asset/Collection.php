<?php
namespace BaconAssetLoader\Asset;

use Zend\Filter\Filter,
    Zend\Stdlib\SplPriorityQueue;

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
    public function addFilter($pattern, Filter $filter, $priority = 1,
        $onTheFly = true, $onCompile = true)
    {
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
