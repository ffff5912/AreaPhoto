<?php

namespace AreaPhoto\AppBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AreaPhoto\AppBundle\Providers\ProviderInterface;
use AreaPhoto\AppBundle\Storage\Cache\CacheManager;

class Location implements MediaServiceInterface
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

    public function execute($lat, $lng, $distance = 300)
    {
        try {
            $location = $this->storage->get('location')->build($lat, $lng, function ($lat, $lng) use ($distance) {
                return $this->search($lat, $lng, $distance);
            });
            $this->storage->get('location')->set($lat, $lng, $location);

            $media = array_map(function ($data) {
                $result = $this->storage->get('media')->build($data['id'], function ($id) {
                    return $this->fetch($id);
                });
                return $result;
            }, $location);

            return $media;
        } catch (HttpException $e) {
            throw new HttpException($e->getCode(), sprintf('The HttpException. BAD REQUEST'));
        }
    }

    public function fetch($location_id, array $query = [])
    {
        $end_point = sprintf($this->end_points['recent'], $location_id);
        $media = $this->instagram_provider->get($end_point, $query);

        return $media['data'];
    }

    public function search($lat, $lng, $distance)
    {
        $query['query'] = ['lat' => $lat, 'lng' => $lng, 'distance' => $distance];
        $location = $this->instagram_provider->get($this->end_points['search'], $query);

        return $location['data'];
    }
}
