<?php

namespace AppBundle\Providers;

use Guzzle\Http\Client;

class GoogleProvider implements ProviderInterface
{
    protected $client;

    public function __construct(Client $client, array $config = [])
    {
        $this->client = $client;
    }
}
