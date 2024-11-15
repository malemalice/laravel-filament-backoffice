BackOffice Service

Handle
- master data
- orders
- shipping


#build image
docker build --no-cache -t laravel-lts-app .

#run container
docker run -d -p 8080:80 -v $(pwd):/var/www/html --name backoffice-svc laravel-lts-app
docker run -d -p 8080:80 -v $(pwd):/var/www/html --name backoffice-svc laravel-lts-app


#exec bash
docker exec -it backoffice-svc bash
