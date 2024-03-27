build:
	if ! [ -f .env ];then cp .env.example .env;fi
	docker-compose build

up:
	docker-compose up -d
	docker-compose run --rm php-fpm composer install
	docker-compose run --rm php-fpm php artisan key:generate
	docker-compose run --rm php-fpm php artisan migrate
	docker-compose run --rm php-fpm php artisan test
down:
	docker-compose down
php-fpm:
	docker-compose exec php-fpm bash
test:
	docker-compose run --rm php-fpm php artisan test
rlist:
	docker-compose run --rm php-fpm php artisan route:list

