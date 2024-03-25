Test project "Todo" instructions by Aleksey Derevyanko
To run app just follow this few steps:

create .env or rename .env.example file and replace db connection section by these lines:

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=todo
DB_USERNAME=root
DB_PASSWORD=

in root directory run < make build > in command line. This will download and install images via docker-compose.

run docker containers with < make up >

Enter to the app container  - run < make php-fpm >

Run < composer install >

Run php artisan key:generate

Run migrations - php artisan migrate   Test database is ready


Well done)

