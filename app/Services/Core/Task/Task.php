<?php

namespace App\Services\Core\Task;

use Cache;
use Closure;

/**
 * Class Task
 * @method mixed cache()
 * @package App\Services\Core\Task
 */
abstract class Task
{
    public $cacheTime = 1;
    public $cacheName;
    public $runFunctionName = 'run';
    public $cacheFunctionName = 'cache';

    public function __call($name, $arguments)
    {
        if($name == $this->cacheFunctionName)
            return call_user_func([$this, 'checkCache'], $arguments);
    }

    public function checkCache()
    {
        $args = func_get_args()[0];
        $cacheName = $this->cacheName ?: $this->generateCacheName($args);

        return Cache::remember($cacheName, $this->cacheTime, function () use ($args) {
            return call_user_func_array([$this, $this->runFunctionName], $args);
        });
    }

    public function cacheName($name)
    {
        $this->cacheName = $name;

        return $this;
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

    private function generateCacheName($args)
    {
        $name[] = class_basename($this);

        if( ! empty($args))
        {
            foreach ($args as $arg)
            {
                if($arg instanceof Closure)
                    continue;

                if(is_array($arg) OR is_object($arg))
                {
                    dd(md5(serialize($arg)));
                    $name[] = md5(serialize($arg));
                }
                else
                    $name[] = (string) md5($arg);
            }
        }

        return implode('_', $name);
    }

}