up:
	docker-compose up -d
build:
	docker-compose up --build -d
down:
	docker-compose down
php-fpm:
	docker-compose exec php-fpm bash

