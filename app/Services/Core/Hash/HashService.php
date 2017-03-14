<?php

namespace App\Services\Core\Hash;

use Vinkla\Hashids\HashidsManager;

class HashService
{
    private $service;
    private $type;

    /**
     * HashService constructor.
     * @param $type
     */
    public function __construct($type = null)
    {
        $this->service = app()->make(HashidsManager::class);
        $this->type = $type ?: 'main';
    }

    public static function type($type)
    {
        return new self($type);
    }

    public function encode($id)
    {
        return $this->service->connection($this->type)->encode($id);
    }

    public function decode($hash)
    {
        $id = $this->service->connection($this->type)->decode($hash);

        return ! empty($id) ? $id[0] : null;
    }
}