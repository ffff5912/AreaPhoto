<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(maxage="86400")
 */

class MediaController extends FOSRestController implements ClassResourceInterface
{
    /**
     * TODO: validation, response
     * @param  string $location_id
     * @return
     */
    public function getLocationAction(Request $request)
    {
        $token = $request->headers->get('X-CSRF-Token');
        if (false === $this->get('form.csrf_provider')->isCsrfTokenValid('default', $token)) {
            throw new AccessDeniedHttpException('CSRF token is invalid.');
        }
        $location = $this->get('app.service.location');
        $media = $location->execute($request->query->get('lat'), $request->query->get('lng'));
        if (0 === count($media)) {
            throw new NotFoundHttpException(sprintf('The resource lat:\'%s\' lng:\'%s\' was not found.', $request->query->get('lat'), $request->query->get('lng')));
        }
        $view = $this->view($media, 200, $this->getResponseHeader());

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
