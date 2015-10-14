<?php

namespace AppBundle\Providers;

use Guzzle\Http\Client;

interface ProviderInterface
{
    public function __construct(Client $client, array $config);
}
