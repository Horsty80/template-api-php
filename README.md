[![GitHub Actions](https://github.com/api-platform/core/workflows/CI/badge.svg)](https://github.com/api-platform/core/actions?workflow=CI)
[![Codecov](https://codecov.io/gh/api-platform/core/branch/master/graph/badge.svg)](https://codecov.io/gh/api-platform/core/branch/master)
[![SymfonyInsight](https://insight.symfony.com/projects/92d78899-946c-4282-89a3-ac92344f9a93/mini.svg)](https://insight.symfony.com/projects/92d78899-946c-4282-89a3-ac92344f9a93)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/api-platform/core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/api-platform/core/?branch=master)

Projet à partir d'ApiPlatform !

# Boilerplate API

## Composants

-   Caddy for serving
-   Postgrsql for database
-   Symfony for API

## Start project

All with docker-compose

```bash
docker-compose build --pull --no-cache
docker-compose up -d
```

You can go to `https://localhost/docs` to see the swagger of this API

After that you can add fixture data for users

```bash
docker-compose exec php bin/console hautelook:fixtures:load
```

## Localy Testing

For local testing you need to create testing db (like in the CI)

```bash
docker-compose exec -T php bin/console -e test doctrine:database:create
docker-compose exec -T php bin/console -e test doctrine:migrations:migrate --no-interaction
```

After that you can add fixture data

```bash
docker-compose exec php bin/console -e test hautelook:fixtures:load
```

To run test `php bin/console -e test`

## Authentication - JWT

All JWT authentication is already implemented and tested, you just need to create your own key.
First run this command to generate JWT secrets ! don't push it to git !

```bash
docker-compose exec php sh -c '
    set -e
    apk add openssl
    php bin/console lexik:jwt:generate-keypair
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```

After that in `.env` file you have JWT env variable and you have in `api/config/jwt` folder with file `private.pem` and `public.pem` ! don't push it to git !

```
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=passphrase_generated
```

That's it !
