<?php

namespace AreaPhoto\AppBundle\Providers;

use Guzzle\Http\Client;

class ProviderFactory
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  array  $config
     * @return InstagramProvider
     */
    public function createInstagramProvider(array $config)
    {
        return new InstagramProvider($this->client, $config);
    }

    public function createGoogleProvider(array $config)
    {
    }
}
