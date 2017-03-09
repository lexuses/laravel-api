<?php

namespace App\Services\Core\Response;

use App\Services\Core\Transformer\Transformer;
use ErrorException;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Resource\Collection as FractalCollection;
use League\Fractal\Resource\Item as FractalItem;

class ResponseManager
{
    public $rootName = 'data';
    public $metaName = 'meta';
    private $transformer;
    private $resource;
    private $type;
    private $meta = [];

    public function transform($transformer)
    {
        if( ! is_object($transformer) OR ! $transformer instanceof Transformer)
            throw new ErrorException('Object is not a transformer');

        $this->transformer = $transformer;

        return $this;
    }

    private function doTransform()
    {
        if( ! $this->transformer)
            throw new ErrorException('Transformer not set');

        $fractal = new FractalManager();

        if($this->type == 'collection')
            $resource = new FractalCollection($this->resource, $this->transformer);
        elseif($this->type == 'item')
            $resource = new FractalItem($this->resource, $this->transformer);

        return $fractal->createData($resource)->toArray();
    }

    public function collection($collection)
    {
        $this->type = 'collection';

        if( ! $collection instanceof Collection)
            throw new ErrorException('Object is not a collection');

        $this->resource = $collection;

        return $this;
    }

    public function item($item)
    {
        $this->type = 'item';
        $this->resource = $item;

        return $this;
    }

    public function data($data)
    {
        $this->type = 'data';

        if($data instanceof Collection)
            $this->resource = $data->toArray();
        elseif (is_array($data))
            $this->resource = $data;

        return $this;
    }

    public function setMeta($data)
    {
        if(is_array($data))
            $this->meta = $data;

        return $this;
    }

    public function addMeta($key, $value = null)
    {
        if(is_array($key))
            $this->meta[key($key)] = $key[key($key)];
        else
            $this->meta[$key] = $value;

        return $this;
    }

    public function send()
    {
        if($this->type == 'data')
            $content = $this->resource;
        else
            $content = $this->doTransform();

        if( ! empty($this->meta))
            $content[$this->metaName] = $this->meta;

        return response()->json($content);
    }
}