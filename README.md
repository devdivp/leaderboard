# Leaderboard Application


## Requirements

- PHP: `v7.4.30`
- MySQL: `v8`
- Docker


## Application setup

Note: To avoid any project environment related issue, please setup this application using Docker. This codebase includes `Dockerfile` and `docker-compose.yml`. The `docker-compose.yml` has been created in `version 3`.

### Please follow the below steps:

- Install Docker on your system.
- Open terminal.
- Clone this repository using below command:

      git clone https://github.com/devdivp/leaderboard.git

- Using terminal go inside project directory **_leaderboard_**
- Run the below docker command from the root:

      docker compose up

- The above command will download all the images and then it will start all the required containers.
- Once the above command is executed completely, run the below composer command:

      docker-compose exec php74-service composer install

- Create database schema:

      docker-compose exec php74-service vendor/bin/doctrine orm:schema-tool:create

- Once the above commands are executed successfully.
Now the application is up and running now and is accessible via below links.


## URL to Access Application

- `http://localhost:8000`
- `http://{host-ip}:8000`


## URL to Access phpMyAdmin

- `http://localhost:8080`
- `http://{host-ip}:8080`


## API Information

- `GET` : `http://localhost:8000/users`
- `POST` : `http://localhost:8000/users/create` : `{"name": "Test Name","age": 22,"address": "Test Address"}`
- `DELETE` : `http://localhost:8000/users/create?id={userid}`
- `GET` : `http://localhost:8000/leaderboard`
- `GET` : `http://localhost:8000/leaderboard/points/plus?id={userid}`
- `GET` : `http://localhost:8000/leaderboard/points/minus?id={userid}`
- `GET` : `http://localhost:8000/leaderboard/user?id={userid}`


## Test Cases

### Run test cases using below command:

      docker-compose exec php74-service php ./vendor/bin/phpunit

