# RestAPITest

## Installation

Install via Composer:
```bash
composer require litvinab/rest-api-test --dev
```

## Enable Bundle
In your `app/AppKernel.php` file, add the following to your list of dev bundles:
```php
<?php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        // ...

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            // ...
            $bundles[] = new Litvinab\Bundle\RestApiTestBundle\RestApiTestBundle();
        }

        // ...
    }
}

```

## Application Configuration

1. Setup `parameters_test.yml` with test database name and connection details;

2. Create data fixtures and configure them right;

3. Add route to `routing_dev.yml`:

```yml
rest_api_test:
    resource: "@RestApiTestBundle/Resources/config/routing.yml"
    prefix:   /api/test
```

## How To Use

### Operations

*Commands*

`bin/console db:test:reload` - drop test database, recreate it and load test data fixtures;
`bin/console db:test:reload --fixtures-only` - reload test data fixtures without database recreation and schema update;

*Controller*

`http://YOUR_DOMAIN/app_dev.php/api/test/db/reload` - reload test data fixtures without database recreation and schema update;


### Test Cases

Extend test cases mentioned below and method to reload database before each test or before required test.

#### FixturesWebTestCase

It can be useful in repositories tests to have the same database state at the beggining of each test.

*Methods:*
```php
getContainer() 

getEntityManager()  

getRepository($name) 

```

#### RestFixturesWebTestCase

*Methods:*
```php
getJson($endpoint, $headers = array())

postJson($endpoint, $json, array $headers = array())

putJson($endpoint, $json, array $headers = array())

deleteJson($endpoint, array $headers = array())

reloadDb() - reload data fixtures by prompt without db recreation and schema update
```

*Asserts:*
```php
assertResponse(Response $response, $expectedStatusCode, $expectedContent)

assertJsonResponse(Response $response, $expectedStatusCode, $expectedJson)

assertResponseCode(Response $response, $expectedStatusCode)
```

### Examples

#### FixturesWebTestCase

```php
<?php
namespace Project\Infrastructure\Repository;

use Litvinab\Bundle\RestApiTestBundle\TestCase\FixturesWebTestCase;
use Project\Domain\Entity\Attribute;

class AttributeRepositoryTest extends FixturesWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        
        $this->reloadDb();
    }
    
    public function test_get_by_slug__success()
    {
        $repo = $this->getRepository('ProjectDomain:Attribute');
        $attr = $repo->getBySlug('currency');

        $this->assertInstanceOf(Attribute::class, $attr);
        $this->assertEquals(2, $attr->getId());
        $this->assertEquals('Product Currency', $attr->getCaption());
    }
}
```

#### RestFixturesWebTestCase

```php
<?php

namespace Project\Application\Controller;

use Litvinab\Bundle\RestApiTestBundle\TestCase\RestFixturesWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AttributeControllerTest extends RestFixturesWebTestCase
{
    public function test_get_task__success()
    {
        $this->reloadDb();
        
        $response = $this->getJson('/api/attributes/currency');

        $expectedJson = '{"caption":"Product Currency","slug":"currency"}';
        $this->assertJsonResponse($response, Response::HTTP_OK, $expectedJson);
    }

    public function test_get_task__failure_with_integer_id()
    {
        $response = $this->getJson('/api/attributes/3');

        $this->assertResponseCode($response, Response::HTTP_NOT_FOUND);
    }
}    
```