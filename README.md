# Installation

1. Clonez le dépot où vous voulez
2. Installez les dépendances : `composer install`
3. Copiez le .env.dist en .env
    - configurer la bdd(db_user,db_password,db_name)
4. Lancez les commandes:
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:migrations:migrate`
    - `php bin/phpunit`