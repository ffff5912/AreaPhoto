<?php

namespace AreaPhoto\AppBundle\Storage\Cache;

use Predis\Client;
use AreaPhoto\AppBundle\Storage\StorageInterface;

class Location implements StorageInterface
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function build($lat, $lng, \Closure $action, $expire = 300)
    {
        $id = $lat . $lng;
        $data = $this->redis->get($id);
        if (false === is_null($data)) {
            return json_decode($data, true);
        }

        return $action($lat, $lng);
    }

    public function set($lat, $lng, $location, $expire = 300)
    {
        $id = $lat . $lng;
        $this->redis->setex($id, $expire, json_encode($location));
    }
}
