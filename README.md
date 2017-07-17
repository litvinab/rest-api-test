# RestAPITest

This symfony bundle is made to simplify testing of your rest API.

It can be useful for functional tests based on data fixtures and for acceptance tests.

Also current bundle provides some aliases of doctrine commands. It was made to prevent copy-paste of the same commands in your CI (for example, in Makefile, in setup of your tests, etc.)

**Contents**

- [Commands](https://github.com/litvinab/rest-api-test#commands);
- [Functional Data Fixtures Based Tests](https://github.com/litvinab/rest-api-test#functional-data-fixtures-based-tests);
- [Acceptance (Behat) Tests](https://github.com/litvinab/rest-api-test#acceptance-behat-tests);


## Installation

Install via Composer:
```bash
composer require litvinab/rest-api-test
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

        $bundles = [
            ...
            new Litvinab\Bundle\RestApiTestBundle\RestApiTestBundle()
        ];

        // ...
    }
}

```

## Commands

- `bin/console db:create` - alias of `doctrine:database:create` command;

- `bin/console db:create-scheme` - alias of `doctrine:schema:create --force` command;

- `bin/console db:load-fixtures` - alias of `doctrine:fixtures:load --no-interaction` command;

- `bin/console db:clear-cache` - clean database metadata, query, result cache at the same time;


## Functional Data Fixtures Based Tests

### Configuration

**1.** Setup `parameters_test.yml` with test database name and connection details;

**2.** Create data fixtures and configure them right;

### How To Use

Extend test cases mentioned below and call method to reload test data fixtures before each test or before specified test.

Call `db:create`, `db:create-schema` before using to create test database and create database schema;


#### FixturesWebTestCase

It can be useful in repositories tests to have the same database state at the beggining of each test.

Methods:
```php
getContainer() 

getEntityManager()  

getRepository($name) 

```

#### RestFixturesWebTestCase

Methods:
```php
getJson($endpoint, $headers = array())

postJson($endpoint, $json, array $headers = array())

putJson($endpoint, $json, array $headers = array())

deleteJson($endpoint, array $headers = array())

reloadDb() - reload data fixtures by prompt without db recreation and schema update
```

Asserts:
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

## Acceptance (Behat) Tests

### Configuration

**1.** Add route to `routing.yml`:

```yml
rest_api_test:
    resource: "@RestApiTestBundle/Resources/config/routing.yml"
    prefix:   /api/test
```

**2.** Add parameters to `parameters.yml` (`rest_api_test.access_token` value should be secured enough):
```yml
    rest_api_test.controller_on: true
    rest_api_test.access_token: "5j%]4fX.)z[HTR{M"
```    

`rest_api_test.controller_on` (*false* by default) - parameter to turn ON/OFF endpoint to reload database in symfony `prod` environment.
 
 **Be careful!** It should be turned ON for acceptance tests (external tests of rest API) only.  
 **Do not turn ON it at production server!**
 It would be good to setup additional security rules for this endpoint, for example, white list of IPs.
  
 `rest_api_test.access_token` (*empty string* by default) - random strong password, for example, `5j%]4fX.)z[HTR{M`;
This token required to access to reload db endpoint; It will be applied only if`rest_api_test.controller_on` is set to `true`;
 

### How To Use

**Your application side:**

**1.** Setup data fixtures if they weren't setup yet;

**2.** Setup separated application instance with **not production database**;

Bundle provides possibility to reload data fixtures in `prod` symfony environment to make possible an end-user testing;


**3.** check configuration setup in `parameters.yml`;


**Behat tests runner side:**

Make `GET` request to reload database state of your application. It should be made before each acceptance test.

Endpoint: `http://YOUR_DOMAIN/api/test/db/reload`

Custom header: `Rest-API-Test-Access-Token: 5j%]4fX.)z[HTR{M`  (value is value of `rest_api_test.access_token` parameter);

As result database mentioned in `parameters.yml` should be reloaded with your test data fixtures;
