<?php

namespace AppBundle\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    /**
     * Returns the headers.
     *
     * @return array An array of headers
     */
    public function getCachingHeader()
    {
        $response = new Response();
        $response->setPublic();
        $response->setSharedMaxAge(30);

        return $response->headers->allPreserveCase();
    }
}
