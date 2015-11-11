<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use AppBundle\Form\Type\LocationType;
use AppBundle\Entity\Location;

/**
 * @Cache(maxage="86400")
 */

class MediaController extends FOSRestController implements ClassResourceInterface, TokenAuthenticatedController
{

    /**
     *
     * @param  Request $request
     * @return json|NotFoundHttpException
     */
    public function getLocationAction(Request $request)
    {
        $form = $this->get('app.form.location')->process($request);
        if (!$form->isValid()) {
            return $form;
        }

        $location_service = $this->get('app.service.location');
        $location = $this->get('app.form.location')->getData();
        $media = $location_service->execute($location->getLat(), $location->getLng(), $location->getDistance());
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource lat:\'%s\' lng:\'%s\' was not found.', $location->getLat(), $location->getLng()));
        }
        $view = $this->view($media, Response::HTTP_OK, $this->getResponseHeader());

        return $this->handleView($view);
    }

    public function getLocationRecentAction(Request $request)
    {
        $form = $this->get('app.form.location.recent')->process($request);
        if (!$form->isValid()) {
            return $form;
        }
        $location_service = $this->get('app.service.location');
        $location = $this->get('app.form.location.recent')->getData();
        $media = $location_service->fetch($location->getId());
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource location_id:\'%s\'  was not found.', $location->getId()));
        }
        $view = $this->view($media, Response::HTTP_OK, $this->getResponseHeader());

        return $this->handleView($view);
    }

    /**
     * Returns the headers.
     *
     * @return array An array of headers
     */
    private function getResponseHeader()
    {
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(30);

        return $response->headers->allPreserveCase();
    }
}
