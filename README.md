# Installation

1. Clonez le dépot où vous voulez
    - `git clone https://github.com/ARDINStephane/Parks.git <nom>`
    - `cd <nom>`
2. Installez les dépendances : `composer install`
3. Copiez le .env.dist en .env
    - configurer la bdd(db_user,db_password,db_name)
4. Lancez les commandes:
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:migrations:migrate`
5. Changer le nom de la Base de données dans le .env afin d'en créer une de test
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:migrations:migrate`
    - Dans le .env.test rajouter et configurer: DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
6. Jouez les fixtures : `php bin/console d:f:l --no-interaction`
7. Authentification : 
    - `mkdir config/jwt`
    - `openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096`
    - `openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout`
    - Dans le fichier .env modifier l'entrée "JWT_PASSPHRASE" avec la pass phrase utilisée pour générer les clefs
    - Requete d'autentification:
        * `curl -X POST -H "Content-Type: application/json" http://localhost:8000/api/login_check -d '{"username":"admin","password":"admin"}'`
        * Ajouter dans les header de toutes les requetes le token reçu: Authorization: Bearer {token} 
8. Tester:
    - `php bin/phpunit`
 
# Back Office
    - <domain>/admin
 
# Api
    - documentation: <domain>/api
    - recherche d'un parking: GET <domain>/api/parkings/simple?nom= 
    - recherche d'un des booking libres: GET <domain>api/parkings/<id>/bookings?dateFin[strictly_before]=now&page=1 
 
