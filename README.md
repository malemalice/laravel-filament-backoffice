BackOffice Service

Handle
- master data
- orders
- shipping


#build image
docker build --no-cache -t laravel-lts-app .
docker build -t laravel-lts-app .


#run container
docker run -d -p 8080:80 -v $(pwd):/var/www/html --name backoffice-svc laravel-lts-app
docker run -d -p 8080:80 -v $(pwd):/var/www/html --name backoffice-svc laravel-lts-app


#exec bash
docker exec -it backoffice-svc bash


#LOGS
composer create-project laravel/laravel nama-proyek --prefer-dist
copy folder to root
composer require filament/filament:"^3.2" -W
 
php artisan filament:install --panels
panel name = admin

php artisan migrate

php artisan make:model Product -m
php artisan make:model Category -m

php artisan migrate

php artisan make:filament-page ViewCategory --resource=CategoryResource --type=ViewRecord

php artisan make:factory CategoryFactory