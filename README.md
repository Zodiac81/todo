## Test project "API Todo" by Aleksey Derevyanko

### To run app just follow this few steps:

* Open console terminal in project root directory and run < make build > command. 
  It will download and install uses images via docker-compose.

* Replace db connection section in .env file:

    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=todo
    DB_USERNAME=root
    DB_PASSWORD=

* Run docker containers with < make up > command. 
  Also it will install all composer dependencies and run laravel migrations and unit tests

* When you need to run unit tests separately use < make test > command.

### USE API VIA SWAGGER*

Add to .env value L5_SWAGGER_CONST_HOST=http://localhost:8080/api
Open browser and type http://localhost:8080/api/docs
Use SignUp route to get your personal token. Copy token and paste it in 'Authorization' modal window. 
Don`t forget to type **Bearer** before token. And now you are free to test TODO API.

### USE API VIA POSTMAN 
   
 Route list command  < make rlist > to view api routes

