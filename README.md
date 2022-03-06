[![GitHub Actions](https://github.com/api-platform/core/workflows/CI/badge.svg)](https://github.com/api-platform/core/actions?workflow=CI)
[![Codecov](https://codecov.io/gh/api-platform/core/branch/master/graph/badge.svg)](https://codecov.io/gh/api-platform/core/branch/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/api-platform/core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/api-platform/core/?branch=master)

Projet à partir d'ApiPlatform !
Pour la docs associé ça se passe ici [Docs api platform](https://api-platform.com/docs)

# Boilerplate API

## Composants

-   Caddy pour les redirections serveur
-   Postgrsql pour la base de données
-   Symfony pour l'API

## Start project

Tout fonction avec docker et docker-compose

```bash
docker-compose build --pull --no-cache
docker-compose up -d
```

Vous pouvez aller sur `https://localhost/docs` pour accéder au swagger de l'API

Après ça vous pouvez ajouter de la data `fixture` pour les utilisateurs

```bash
docker-compose exec php bin/console hautelook:fixtures:load
```

## Test local

Pour tester localement l'application vous devez créer une base de donnée local (docker-compose n'ai pas configuré pour creer une base de test seulement celle de dev)

```bash
docker-compose exec -T php bin/console -e test doctrine:database:create
docker-compose exec -T php bin/console -e test doctrine:migrations:migrate --no-interaction
```

Après ça pareil on ajoute de la data

```bash
docker-compose exec php bin/console -e test hautelook:fixtures:load
```

Et on peut lancer les tests unitaires `docker-compose exec php bin/console -e test`

## Authentification - JWT

Tout le setup JWT est implémenté et testé, vous n'avez qu'a créer des key pour la création du token.
Lancer la commande qui suit, attention à ne pas versionné les données sensibles.

```bash
docker-compose exec php sh -c '
    set -e
    apk add openssl
    php bin/console lexik:jwt:generate-keypair
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```

Après ça dans le fichier `.env` vous trouverez les variables d'env JWT et un dossier `api/config/jwt` avec les fichier `private.pem` and `public.pem`. Pareil attention à ne pas versionner les données sensibles.

```
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=passphrase_generated
```

Et voilà !

## Routes disponible

Only 2 routes are public
Seulement 2 routes sont publics, les autres sont accessible seulement à un utilisateur admin.

-   https://localhost/users/register -> Pour créer un utilisateur avec le role `ROLE_USER`
-   https://localhost/authentication_token -> Pour récupérer son JWT
