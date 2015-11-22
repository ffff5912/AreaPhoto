<?php

namespace AreaPhoto\AppBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LocationTest extends WebTestCase
{
    protected static $media_schema;

    public static function setUpBeforeClass()
    {
        self::$media_schema = [
            'attribution',
            'tags',
            'type',
            'location',
            'comments',
            'filter',
            'created_time',
            'link',
            'likes',
            'images',
            'users_in_photo',
            'caption',
            'user_has_liked',
            'id',
            'user',
        ];
    }

    public function setUp()
    {
        $this->client = static::createClient();
        $this->service = $this->client->getContainer()->get('app.service.location');
    }

    public function testExecuteSuccess()
    {
        $lat = '35.79390245637972';
        $lng = '139.80063915252686';
        $distance = '100';
        $media = $this->service->execute($lat, $lng, $distance);

        $this->assertCount(2, $media);
        $this->assertCount(0, array_diff_key($media[0][0], array_flip(self::$media_schema)));
        $this->assertCount(0, array_diff_key($media[1][0], array_flip(self::$media_schema)));
    }

    public function testFetchSuccess()
    {
        $location_id = 514553024;
        $media = $this->service->fetch($location_id);

        $this->assertCount(0, array_diff_key($media[0], array_flip(self::$media_schema)));
        $this->assertEquals(514553024, $media[0]['location']['id']);
    }

    public function testSearchSuccess()
    {
        $lat = '35.79390245637972';
        $lng = '139.80063915252686';
        $distance = '100';
        $location = $this->service->search($lat, $lng, $distance);

        $this->assertCount(4, $location[0]);
        $this->assertEquals(35.794313281, $location[0]['latitude']);
        $this->assertEquals(514553024, $location[0]['id']);
        $this->assertEquals(139.800546743, $location[0]['longitude']);
    }
}
