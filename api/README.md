# API

## TODO :

-   create alias for Symfony command

## Ressources :

-   To hash password in fixture : [Article](https://yalit.be/blog/2020/11/08/symfony-hautelook-alice-hash-passwords-at-fixtures-load/)

## Symfony command :

-   Create/Update entity :
    ```
    docker-compose exec php \
        bin/console make:entity --api-resource
    ```
-   Generate new database migration :
    ```
    docker-compose exec php \
        bin/console doctrine:migrations:diff
    docker-compose exec php \
        bin/console doctrine:migrations:migrate
    ```
-   Generate new test database migration :
    ```
    docker-compose exec php \
        bin/console -e test doctrine:migrations:diff
    docker-compose exec php \
        bin/console -e test doctrine:migrations:migrate
    ```
-   Create database
    ```
    docker-compose exec -T php bin/console -e test doctrine:database:create
          docker-compose exec -T php bin/console -e test doctrine:migrations:migrate --no-interaction
    ```
-   Run test
    ```
    docker-compose exec php bin/phpunit
    ```
-   Run command on test db `php bin/console -e test`
-   Load fixture on db
    ```
    docker-compose exec php bin/console hautelook:fixtures:load
    docker-compose exec php bin/console -e test hautelook:fixtures:load
    ```

## API design :

To expose intelligent class to other class like Weather (who calcul current T°) you request for Place and get the T° of this Places.
You need to add ApiRessource annotation with `Random` Controller to allow Places request Weather

```php
<?php
#[ApiResource(
    itemOperations: [
        'get' => [
            'method' => 'GET',
            'controller' => SomeRandomController::class,
        ],
    ],
)]
class Weather {
    /** */
}
```

After that don't forget to add the new available route in OpenApiFactory to filter route that we don't want to show

## Mercure - Push notification :

-   [Mercure - Docs](https://mercure.rocks/docs/getting-started#subscribing)
