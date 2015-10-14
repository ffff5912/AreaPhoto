<?php

namespace AppBundle\Storage\Cache;

use Predis\Client;
use AppBundle\Storage\StorageInterface;

class Media implements StorageInterface
{
    private $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    public function build($id, \Closure $action, $expire = 300)
    {
        $data = $this->redis->get($id);
        if (false === is_null($data)) {
            return json_decode($data, true);
        }
        $media = $action($id);
        $this->redis->setex($id, $expire, json_encode($media));
        return $media;
    }
}
