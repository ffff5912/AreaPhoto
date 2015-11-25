<?php

namespace AreaPhoto\AppBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TagTest extends WebTestCase
{
    protected static $hash_tag_schema;

    public static function setUpBeforeClass()
    {
        self::$hash_tag_schema = [
            'media_count',
            'name',
        ];
    }

    public function setUp()
    {
        $this->client = static::createClient();
        $this->service = $this->client->getContainer()->get('app.service.tag');
    }


    public function testSearchSuccess()
    {
        $tag = $this->service->search('東京');
        $this->assertCount(0, array_diff_key($tag[0], array_flip(self::$hash_tag_schema)));

        return $tag[0]['name'];
    }

    /**
     * @depends testSearchSuccess
     */
    public function testExecuteSuccess($tag)
    {
        $media = $this->service->execute($tag);

        $this->assertCount(0, array_diff_key($media[0], array_flip(LocationTest::$media_schema)));
    }
}
