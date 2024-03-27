build:
	if ! [ -f .env ];then cp .env.example .env;fi
	docker-compose build
	docker-compose run --rm php-fpm php composer install
	docker-compose run --rm php-fpm php artisan migrate
up:
	docker-compose up -d
down:
	docker-compose down
php-fpm:
	docker-compose exec php-fpm bash
test:
	docker-compose run --rm php-fpm php artisan test
