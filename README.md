# sonata-admin-postgresql-case-insentive-like-bundle
This Symfony bundle provides the ability to make insentive LIKE search on SonataAdmin with PostgreSQL database
It makes all search **case insensitive**.

## Install

Register the bundle to your `app/AppKernel.php`

```php
    // ...
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Lpweb\SonataAdminPostgreSQLCaseInsensitiveLikeBundle\LpwebSonataAdminPostgreSQLCaseInsensitiveLikeBundle(),
        ];
        // ...
    }
```

Now all the CONTAINS search in you sonata admin will be made case insensitive