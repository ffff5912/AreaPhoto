<?php

namespace AreaPhoto\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use AreaPhoto\AppBundle\Controller\TokenAuthenticatedController;

class TokenListener
{
    private $csrf_provider;

    public function __construct(CsrfProviderInterface $csrf_provider)
    {
        $this->csrf_provider = $csrf_provider;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {
            $token = $event->getRequest()->headers->get('X-CSRF-Token');
            if (false === $this->csrf_provider->isCsrfTokenValid('default', $token)) {
                throw new AccessDeniedHttpException('CSRF token is invalid.');
            }
        }
    }
}
