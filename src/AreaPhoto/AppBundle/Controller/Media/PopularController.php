<?php

namespace AreaPhoto\AppBundle\Controller\Media;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use AreaPhoto\AppBundle\Form\MediaFormInterface;
use AreaPhoto\AppBundle\Services\MediaServiceInterface;
use AreaPhoto\AppBundle\Traits\ResponseTrait;
use AreaPhoto\AppBundle\Controller\TokenAuthenticatedController;

class PopularController extends FOSRestController implements ClassResourceInterface, TokenAuthenticatedController
{

    /**
     * @var MediaServiceInterface
     */
    private $service;

    /**
     * @param MediaServiceInterface $service [instance of AreaPhoto\AppBundle\Services\Pupular]
     */
    public function __construct(MediaServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param  Request $request
     * @return json|NotFoundHttpException
     */
    public function getAction(Request $request)
    {
        $media = $this->service->execute();

        if (0 === count($media)) {
            throw new NotFoundHttpException('The resource popular was not found.');
        }

        return $this->view($media, Response::HTTP_OK);
    }
}
