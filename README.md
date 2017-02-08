YproxApiClientBundle
====================

Installation
------------

Require
[`yproximite/yprox-api-client-bundle`](https://packagist.org/packages/yproximite/yprox-api-client-bundle)
to your `composer.json` file:

```json
$ composer require yproximite/yprox-api-client-bundle
```

Register the bundle in `app/AppKernel.php`:

```php
// app/AppKernel.php
public function registerBundles()
{
    return [
        // ...
        new Yproximite\Bundle\YproxApiClientBundle\YproxApiClientBundle(),
    ];
}
```

Configuration
-------------

Here is the configuration reference:

```yaml
# app/config/config.yml
ypox_api_client:

    # Identifier of the service that represents "Http\Client\HttpClient"
    http_client: httplug.client.guzzle6

    clients:

        # Simple example
        default:
            api_key: xxxxx

        # Advanced example
        custom:
            api_key: yyyyy
            base_url: http://api.host.com
```

Usage
-----

```php
// yprox_api_client.service_aggregator.<client_name_from_config>
$api = $this->get('yprox_api_client.service_aggregator.default');

$message = new ArticleListMessage();
$message->setSiteId(1);

// Yproximite\Api\Model\Article\Article[]
$articles = $api->article()->getArticles($message);
```
