# RadiantPHP Microframework

RadiantPHP is a lightweight microframework for building PHP web applications. It provides a simple and elegant structure to develop web projects with ease. With RadiantPHP, you can quickly build RESTful APIs, web services, or small to medium-sized websites.

The RadiantPHP ServerRequestFactory provides a simple and convenient way to handle HTTP requests and responses in your PHP application. This documentation will guide you on how to integrate the ServerRequestFactory into your `index.php` file to handle incoming requests and send appropriate responses.

## Installation

In you project php, opem your terminal and enter with this commands!

```bash
composer require jacksonsr45/radiante-php
```

## Usage

Follow the steps below to integrate the RadiantPHP ServerRequestFactory into your `index.php` file:

1. **Import the required class**:

```php
<?php

use Jacksonsr45\RadiantPHP\ServerRequestFactory;
```

2. **Include the Composer autoloader and set the path to your `routes.php` file:**

```php
require_once __DIR__ . '/../vendor/autoload.php';

$pathToRoute = __DIR__ . '/routes.php';
```

3. **Create the ServerRequest and handle the request:**

```php
$request = ServerRequestFactory::createServerRequest($pathToRoute);
$response = ServerRequestFactory::handleRequest($request);
```

4. **Send the HTTP response:**

```php
ServerRequestFactory::sendHttpResponse($response);
```

**Example:**

Here's a complete example of how your `index.php` file should look after integrating the RadiantPHP ServerRequestFactory:

```php
<?php

use Jacksonsr45\RadiantPHP\ServerRequestFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$pathToRoute = __DIR__ . '/routes.php';
$request = ServerRequestFactory::createServerRequest($pathToRoute);
$response = ServerRequestFactory::handleRequest($request);
ServerRequestFactory::sendHttpResponse($response);
```

Make sure to replace `'../vendor/autoload.php'` with the correct path to your Composer autoloader if it differs.

With these simple steps, you have successfully integrated the RadiantPHP ServerRequestFactory into your index.php file, allowing you to handle incoming HTTP requests and send appropriate responses in your PHP application.
