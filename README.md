# Projet ERP

## Api
* PHP 7.4
* Phalcon 4
* Phinx
* MySql

## Frontend
* Vue.js 3
* Bootstrap 5
* Vuex 4
* Vue Router 4

## Installation
* docker-compose up
* docker exec -ti api bash
* ./vendor/bin/phinx migrate -e development
* ./vendor/bin/phinx seed:run

## Tests
API
* docker exec -ti api bash
* ./vendor/bin/phpunit application/tests/Unit --coverage-text

## Access
Frontend : http://localhost:3000

### Admin account
* name : admin
* password : 123456

### User account
* name : user
* password : 123456
