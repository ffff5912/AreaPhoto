<?php

namespace AreaPhoto\AppBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AreaPhoto\AppBundle\Providers\ProviderInterface;
use AreaPhoto\AppBundle\Storage\Cache\CacheManager;

class Popular implements MediaServiceInterface
{
    private $instagram_provider;
    private $end_point;
    private $storage;

    public function __construct(ProviderInterface $instagram_provider, $end_point, CacheManager $storage)
    {
        $this->instagram_provider = $instagram_provider;
        $this->end_point = $end_point;
        $this->storage = $storage;
    }

    public function execute()
    {
        try {
            $media = $this->instagram_provider->get($this->end_point);

            return $media['data'];
        } catch (HttpException $e) {
            throw new HttpException($e->getCode(), sprintf('The HttpException. BAD REQUEST'));
        }
    }
}
