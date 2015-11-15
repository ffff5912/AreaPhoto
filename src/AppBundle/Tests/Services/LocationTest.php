<?php

namespace AppBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LocationTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
        $this->service = $this->client->getContainer()->get('app.service.location');
    }

    public function testFetchSuccess()
    {
        $location_id = 514553024;
        $result = $this->service->fetch($location_id);
        $this->assertEquals(15, count($result[0]));
        $this->assertEquals(514553024, $result[0]['location']['id']);
    }
}
