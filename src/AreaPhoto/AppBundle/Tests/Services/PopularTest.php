<?php

namespace AreaPhoto\AppBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PopularTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
        $this->service = $this->client->getContainer()->get('app.service.popular');
    }

    public function testExecuteSuccess()
    {
        $media = $this->service->execute();

        $this->assertCount(0, array_diff_key($media[0], array_flip(LocationTest::$media_schema)));
    }
}
