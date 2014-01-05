# 20steps/wemonit-bundle (twentystepsWeMonItBundle)

## About

The 20steps WeMonIt Bundle provides a Service-oriented API for Symfony2 applications that need to interact with the WeMonIt monitoring service.

For further information about WeMonIt goto http://www.wemonit.de.

## Features

- [x] WeMonIt accessible as a configurable Symfony2 service.
- [ ] Complete API for WeMonIt.
- [x] Configurable caching of responses to prevent surpassing rate limit.
- [ ] Provide some derived KPIs.
- [ ] Full documentation and some examples.
- [ ] Prepare for open sourcing of 20steps control.

## Installation

Require the bundle by adding the following entry to the respective section of your composer.json:
```
"20steps/wemonit-bundle": "dev-master"
```

Get the bundle via packagist from GitHub by calling:
```
php composer.phar update 20steps/wemonit-bundle
```

Register the bundle in your application by adding the following line to the registerBundles() method of your AppKernel.php:  
```
new twentysteps\Bundle\WeMonItBundle\twentystepsWeMonItBundle()
```

Register services provided by the bundle by adding the following line to the imports section of your config.yml:  
```
- { resource: "@twentystepsWeMonItBundle/Resources/config/services.yml" }
```

Define the following properties in your parameters.yml:  
* twentysteps_wemonit.url - URL of the WeMonIt API - normally should point to "https://wemonit.de/api/".
* twentysteps_wemonit.apikey - API key of your account at WeMonIt.
* twentysteps_wemonit.timeout - Timeout in seconds to apply on calls of the WeMonIt API - you should use 10.
* twentysteps_wemonit.connect_timeout - Connect timeout in seconds to apply on calls to the WeMonIt API - you should use 5.
* twentysteps_wemonit.cache_ttl - Cache TTL to apply on responses of the WeMonIt API - you should use 3600.

## Usage

* Get reference to the WeMonIt service either by adding @twentysteps_wemonit.service as a dependency in your service or by  explicitely getting the service from the container during runtime e.g. by calling $this->get('twentysteps_wemonit.service') in the action of your controller.
* Call any public function provided by Services/WeMonItService.php e.g. getServices() to get the monitoring services listed in WeMonIt.

## Version

This version is not yet complete or usable.

## Author

Helmut Hoffer von Ankershoffen (hhva@20steps.de).