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
use AppBundle\Traits\ResponseTrait;

/**
 * @Cache(maxage="86400")
 */

class MediaController extends FOSRestController implements ClassResourceInterface, TokenAuthenticatedController
{
    use ResponseTrait;

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

        $location = $this->get('app.form.location')->getData();
        $location_service = $this->get('app.service.location');
        $media = $location_service->execute($location->getLat(), $location->getLng(), $location->getDistance());
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource lat:\'%s\' lng:\'%s\' was not found.', $location->getLat(), $location->getLng()));
        }
        $view = $this->view($media, Response::HTTP_OK, $this->getCachingHeader());

        return $this->handleView($view);
    }

    public function getLocationRecentAction(Request $request)
    {
        $form = $this->get('app.form.location.recent')->process($request);
        if (!$form->isValid()) {
            return $form;
        }
        $location = $this->get('app.form.location.recent')->getData();
        $location_service = $this->get('app.service.location');
        $media = $location_service->fetch($location->getId());
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource location_id:\'%s\'  was not found.', $location->getId()));
        }
        $view = $this->view($media, Response::HTTP_OK, $this->getCachingHeader());

        return $this->handleView($view);
    }
}
