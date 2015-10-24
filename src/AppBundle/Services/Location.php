<?php

namespace AppBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Providers\ProviderInterface;
use AppBundle\Storage\StorageInterface;

class Location implements MediaServiceInterface
{
    private $instagram_provider;
    private $end_points;
    private $storage;

    public function __construct(ProviderInterface $instagram_provider, array $end_points, StorageInterface $storage)
    {
        $this->instagram_provider = $instagram_provider;
        $this->end_points = $end_points;
        $this->storage = $storage;
    }

    public function execute($lat, $lng, $distance = 100)
    {
        try {
            $location = $this->search($lat, $lng, $distance);
            $media = array_map(function ($data) {
                $result = $this->storage->build($data['id'], function ($id) {
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
