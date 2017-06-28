<?php

namespace App\Services\Core\Task;

use Cache;

/**
 * Class Task
 * @method mixed cache()
 * @package App\Services\Core\Task
 */
abstract class Task
{
    public $cacheTime = 1;
    private $cacheName;
    public $runFunction = 'run';

    public function __construct()
    {
        $this->cacheName = class_basename($this);
    }

    public function __call($name, $arguments)
    {
        if($name == 'cache')
            return call_user_func([$this, 'checkCache'], $arguments);
    }

    public function checkCache()
    {
        $args = func_get_args()[0];
        return Cache::remember($this->cacheName, $this->cacheTime, function () use ($args) {
            return call_user_func_array([$this, $this->runFunction], $args);
        });
    }

    public function cacheTime($time)
    {
        $this->cacheTime = $time;

        return $this;
    }

    public function clearCache()
    {
        Cache::forget($this->cacheName);

        return $this;
    }

}