# Task Manger

A simple Task Management CRUD application built with Laravel 12 and Vue.js 3.

## Features
- Task CRUD API (Laravel)
- Vue.js frontend interface
- RESTful endpoints
- Validation via Form Requests
- Response formatting via API Resources
- Proper error handling with HTTP status codes

## Teck Stack

- **Backend**: Laravel 12, PHP 8.1+
- **Frontend**: Vue.js  (Composition/Options API)
- **Database**: MySQL

## Installation

- clone the repository

``` 
git clone https://github.com/drokade981/task-manager.git
```

- npm install and composer install
```
composer install
npm install
```

- copy .env.example and rename it to .env
```
cp .env.example .env
```
- create database  
- configure db in .env file
- run migration command
```
php artisan migrate
```

- start the application
```
php artisan serve
```

- run database seeder for some dummy data
```
php artisan db:seed
```