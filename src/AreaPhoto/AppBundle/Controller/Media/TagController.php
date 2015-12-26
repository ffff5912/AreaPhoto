<?php

namespace AreaPhoto\AppBundle\Controller\Media;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use AreaPhoto\AppBundle\Services\MediaServiceInterface;
use AreaPhoto\AppBundle\Traits\ResponseTrait;
use AreaPhoto\AppBundle\Controller\TokenAuthenticatedController;

class TagController extends FOSRestController implements ClassResourceInterface, TokenAuthenticatedController
{
    /**
     * @var MediaServiceInterface
     */
    private $service;

    /**
     * @param MediaServiceInterface $service
     */
    public function __construct(MediaServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param  Request $request
     * @return json
     */
    public function getAction(Request $request)
    {
        $media = $this->service->execute($request->get('tag'));

        if (0 === count($media)) {
            throw new NotFoundHttpException('The resource popular was not found.');
        }

        return $this->view($media, Response::HTTP_OK);
    }
}
