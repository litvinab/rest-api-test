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
            $bundles[] = new Litvinab\Bundle\RestApiTestBundle();
        }

        // ...
    }
}

```

## Configure Bundle

### Application Configuration

...