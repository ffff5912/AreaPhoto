<?php

namespace AreaPhoto\AppBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PopularTest extends WebTestCase
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
        $this->service = $this->client->getContainer()->get('app.service.popular');
    }

    public function testExecuteSuccess()
    {
        $media = $this->service->execute();

        $this->assertCount(0, array_diff_key($media[0], array_flip(self::$media_schema)));
    }
}
