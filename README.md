# Installation

1. Clonez le dépot où vous voulez
2. Installez les dépendances : `composer install`
3. Copiez le .env.dist en .env
    - configurer la bdd(db_user,db_password,db_name)
4. Lancez les commandes:
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:migrations:migrate`
    - `php bin/phpunit`
5. Jouez les fixtures : `php bin/console d:f:l --no-interaction`

# Back Office
    - <domain>/admin
 
# Api
    - documentation: <domain>/api
    - recherche d'un parking: GET <domain>/api/parkings/simple?nom= 
    - recherche d'un des booking libres: GET <domain>api/parkings/<id>/bookings?dateFin[strictly_before]=now&page=1 
 
