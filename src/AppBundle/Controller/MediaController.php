<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use AppBundle\Form\MediaFormInterface;
use AppBundle\Services\MediaServiceInterface;
use AppBundle\Traits\ResponseTrait;

/**
 * @Cache(maxage="86400")
 */

class MediaController extends FOSRestController implements ClassResourceInterface, TokenAuthenticatedController
{
    /**
     * @var Traits\ResponseTrait
     */
    use ResponseTrait;

    /**
     * @var Form\LocationForm
     */
    private $form;

    /**
     * @var Services\Location
     */
    private $media_service;

    /**
     *
     * @param MediaFormInterface    $form
     * @param MediaServiceInterface $media_service
     */
    public function __construct(MediaFormInterface $form, MediaServiceInterface $media_service)
    {
        $this->form = $form;
        $this->media_service = $media_service;
    }

    /**
     *
     * @param  Request $request
     * @return json|NotFoundHttpException
     */
    public function getLocationAction(Request $request)
    {
        $form = $this->form->process($request);
        if (!$form->isValid()) {
            return $form;
        }

        $location = $this->form->getData();
        $media = $this->media_service->execute($location->getLat(), $location->getLng(), $location->getDistance());
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource lat:\'%s\' lng:\'%s\' was not found.', $location->getLat(), $location->getLng()));
        }

        return $this->view($media, Response::HTTP_OK, $this->getCachingHeader());
    }

    /**
     *
     * @param  Request $request
     * @return json|NotFoundHttpException
     */
    public function getLocationRecentAction(Request $request)
    {
        $form = $this->form->process($request);
        if (!$form->isValid()) {
            return $form;
        }

        $location = $this->form->getData();
        $media = $this->media_service->fetch($location->getId());
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource location_id:\'%s\'  was not found.', $location->getId()));
        }

        return $this->view($media, Response::HTTP_OK, $this->getCachingHeader());
    }
}
