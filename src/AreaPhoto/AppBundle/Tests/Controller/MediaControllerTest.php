<?php

namespace AreaPhoto\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use AreaPhoto\AppBundle\Controller\MediaController;
use AreaPhoto\AppBundle\Tests\Schema;

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
        $query = ['lat' => '35.79390245637972', 'lng' => '139.80063915252686', 'distance' => '100'];
        $request = new Request($query);

        $location = $this->getLocationMock();
        $location->expects($this->once())
            ->method('getLat')
            ->will($this->returnValue('35.79390245637972'));
        $location->expects($this->once())
            ->method('getLng')
            ->will($this->returnValue('139.80063915252686'));
        $location->expects($this->once())
            ->method('getDistance')
            ->will($this->returnValue('100'));

        $form = $this->getFormMock();
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $location_form = $this->getLocationFormMock($request, $form, $location);

        $service = $this->getMediaServiceMock();
        $media[] = Schema::media();
        $service->expects($this->once())
            ->method('execute')
            ->will($this->returnValue(json_encode($media)));

        $controller = new MediaController($location_form, $service);
        $response = $controller->getLocationAction($request);
        $data = json_decode($response->getData(), true);

        $this->assertInstanceOf('FOS\RestBundle\View\View', $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->getHeaders() === ['cache-control' => ['public, s-maxage=30']]);
        $this->assertTrue($data[0] === Schema::media());
    }

    public function testLocationActionValidError()
    {
        $query = ['lat' => '35.7939024563797a', 'lng' => '139.800639152526b', 'distance' => '100'];
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
        $query = ['lat' => '35.79390245637970', 'lng' => '139.800639152526b', 'distance' => '100'];
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
        $query = ['lat' => '35.79995912279198', 'lng' => '139.7947597503662', 'distance' => '100'];
        $request = new Request($query);

        $location = $this->getLocationMock();
        $location->expects($this->exactly(2))
            ->method('getLat')
            ->will($this->returnValue('35.79995912279198'));
        $location->expects($this->exactly(2))
            ->method('getLng')
            ->will($this->returnValue('139.7947597503662'));
        $location->expects($this->once())
            ->method('getDistance')
            ->will($this->returnValue('100'));

        $form = $this->getFormMock();
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $location_form = $this->getLocationFormMock($request, $form, $location);

        $service = $this->getMediaServiceMock();
        $media = [];
        $service->expects($this->once())
            ->method('execute')
            ->will($this->returnValue($media));

        try {
            $controller = new MediaController($location_form, $service);
            $response = $controller->getLocationAction($request);
            $this->fail();
        } catch (\Exception $e) {
            $this->assertEquals(Response::HTTP_NOT_FOUND, $e->getStatusCode());
            $this->assertInstanceOf('Symfony\Component\HttpKernel\Exception\NotFoundHttpException', $e);
            $this->assertEquals($e->getMessage(), "The resource lat:'35.79995912279198' lng:'139.7947597503662' was not found.");
        }
    }

    private function getLocationMock()
    {
        $location = $this->getMockBuilder('AreaPhoto\AppBundle\Entity\Location')->disableOriginalConstructor()->getMock();

        return $location;
    }

    private function getLocationFormMock(Request $request, $form, $location)
    {
        $location_form = $this->getMockBuilder('AreaPhoto\AppBundle\Form\MediaFormInterface')->disableOriginalConstructor()->getMock();
        $location_form->expects($this->once())
            ->method('process')
            ->with($request)
            ->will($this->returnValue($form));
        $location_form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($location));

        return $location_form;
    }

    private function getFormMock()
    {
        return $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
    }

    private function getMediaServiceMock()
    {
        return $this->getMockBuilder('AreaPhoto\AppBundle\Services\Location')->disableOriginalConstructor()->getMock();
    }
}
