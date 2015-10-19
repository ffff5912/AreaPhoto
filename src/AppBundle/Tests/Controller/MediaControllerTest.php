<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MediaControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient();
        $this->csrf_provider = $this->client->getContainer()->get('form.csrf_provider');
        $token = $this->csrf_provider->generateCsrfToken('default');
        $this->header = ['HTTP_X-CSRF-Token' => $token];
        $this->storage = $this->client->getContainer()->get('snc_redis.default');
    }

    public function testLocationActionSuccess()
    {
        $query = ['lat' => '35.79390245637972', 'lng' => '139.80063915252686'];
        $location_id = '514553024';
        $crawler = $this->client->request(
            'GET',
            '/media/location',
            $query,
            [],
            $this->header
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content, true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertTrue($response->headers->contains('Cache-Control', 'max-age=86400, public, s-maxage=30'));
        $this->assertNotNull($json);
        $this->assertNotNull($this->storage->get($location_id));
        $this->storage->flushDB();
    }

    public function testLocationActionValidError()
    {
        $query = ['lat' => '35.7939024563797a', 'lng' => '139.800639152526b'];
        $crawler = $this->client->request(
            'GET',
            '/media/location',
            $query,
            [],
            $this->header
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content, true);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($json['message'], 'Validation Failed');
    }

    public function testLocationActionCsrfTokenValid()
    {
        $query = ['lat' => '35.79390245637970', 'lng' => '139.800639152526b'];
        $this->header['HTTP_X-CSRF-Token'] = '0000000001';
        $crawler = $this->client->request(
            'GET',
            '/media/location',
            $query,
            [],
            $this->header
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content, true);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($json['error']['exception'][0]['message'], 'CSRF token is invalid.');
        $this->assertEquals($json['error']['exception'][0]['class'], 'Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException');
    }

    public function testLocationActionNotFound()
    {
        $query = ['lat' => '35.79995912279198', 'lng' => '139.7947597503662'];
        $crawler = $this->client->request(
            'GET',
            '/media/location',
            $query,
            [],
            $this->header
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();
        $json = json_decode($content, true);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($json['error']['exception'][0]['message'], "The resource lat:'35.79995912279198' lng:'139.7947597503662' was not found.");
        $this->assertEquals($json['error']['exception'][0]['class'], 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
    }
}
