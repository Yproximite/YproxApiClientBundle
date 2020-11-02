<?php

namespace Yproximite\Bundle\YproxApiClientBundle\Tests;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DummyHttpClient implements HttpClient
{
    public function sendRequest(RequestInterface $request)
    {
        throw new \BadMethodCallException('Method is not implemented.');
    }
}
