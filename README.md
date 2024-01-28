# Booking App

Projet de création d'une application de gestion de prise de rendez-vous moderne et responsive.

## Installation

### Avec Docker

#### Prérequis

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

#### Installation

- Cloner le projet
- Placer vous à la racine du projet
- Commencer par builder l'image avec `docker build . -f ./docker/Dockerfile -t hair-star-image`
- Placer vous dans le dossier docker avec `cd docker`
- Lancer le docker-compose avec `docker-compose up -d`

#### Utilisation

- Rendez-vous sur [localhost:8080](http://localhost:8080)
- Pour arrêter le docker-compose `docker-compose down`

### Sans Docker

#### Prérequis

- [Node.js](https://nodejs.org/en/)
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/fr/)
- [Git](https://git-scm.com/)

#### Installation

- Cloner le projet
- Installer les dépendances avec `composer install` et `npm install`
- Créer la base de données avec `php bin/console doctrine:database:create`
- Créer les tables avec `php bin/console doctrine:migrations:migrate`
- Lancer le serveur avec `php bin/console server:start`
- Créer un le premier utilisateur avec `php bin/console app:create-first-user`

## Auteurs

- [Lazare Fortune](https://lazarefortune.com/)

## License

[MIT](https://choosealicense.com/licenses/mit/)
