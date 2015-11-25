<?php

namespace AreaPhoto\AppBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AreaPhoto\AppBundle\Providers\ProviderInterface;
use AreaPhoto\AppBundle\Storage\Cache\CacheManager;

class Tag implements MediaServiceInterface
{
    private $instagram_provider;
    private $end_points;
    private $storage;

    public function __construct(ProviderInterface $instagram_provider, array $end_points, CacheManager $storage)
    {
        $this->instagram_provider = $instagram_provider;
        $this->end_points = $end_points;
        $this->storage = $storage;
    }

    public function execute($tag)
    {
        try {
            $end_point = sprintf($this->end_points['recent'], $tag);
            $media = $this->instagram_provider->get($end_point);

            return $media['data'];
        } catch (HttpException $e) {
            throw new HttpException($e->getCode(), sprintf('The HttpException. BAD REQUEST'));
        }
    }

    public function search($keyword)
    {
        $query['query'] = ['q' => $keyword];
        $tag = $this->instagram_provider->get($this->end_points['search'], $query);

        return $tag['data'];
    }
}
