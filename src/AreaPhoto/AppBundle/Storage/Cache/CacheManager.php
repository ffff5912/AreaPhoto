<?php

namespace AreaPhoto\AppBundle\Storage\Cache;

use Doctrine\Common\Collections\ArrayCollection;

class CacheManager
{
    private $storage;

    public function __construct(Media $media, Location $location)
    {
        $this->storage = new ArrayCollection;
        $this->storage->set('media', $media);
        $this->storage->set('location', $location);
    }

    public function get($key)
    {
        return $this->storage->get($key);
    }
}
