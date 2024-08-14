# Ticket Api

Assigment Ticket Api Project.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

This projects requires the following:
* PHP 8.3
* MySQL or MariaDB
* NGinx or Apache for the PHP (on Mac, I'd recommend using Laravel Valet - https://laravel.com/docs/11.x/valet)
* Composer

#### Installation on mac

* Install Brew from https://brew.sh/
```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
* Install the needed software
```
brew install php
brew install mysql
```
* If you want to use laravel valet, use this
```
composer global require laravel/valet
valet install
```

#### Installation on Windows

* Install Apache - https://www.apachefriends.org/

### Installing

A step by step series of examples that tell you how to get a development env running
```
git clone https://github.com/Zohair22/ticket_api.git
```

Create a database  and user with the details below:
DB:   dazu_core_local
User: root
Pass:

While in the project root directory, copy the .env.local from the repository and make the file name .env
```
cp .env.local .env
```
You need to create a database with the username/password you have in the .env file (or modify the .env file accordingly)

While in the project root directory, run composer install to bring all the project libraries and dependencies (you must be connected to the internet for this)
```
composer install
```
While in the project root directory, run to add passport supplied keys
```
php artisan passport:keys
```
While in the project root directory, run to create the database tables
```
php artisan migrate
```
Install the test data we've created (only use this on local please)
```
php artisan db:seed --class=TestSeeder
```
While in the project root directory, serve the project
```
php artisan serve
```
Or If you are installed laravel valet
```
valet link
valet secure
```
Now You Can use URL
```
https://api_project.test
```

### Prepared Test Data

The project has multiple seed files to get the project up and running and looking ok.

While in the project root directory, run the following seed (which will include populated test data)
```
php artisan db:seed 
```


### Architecture (Laravel)
Using the following patterns:
* **Application layer**
    * **Controllers**
        - these controllers come from APIs or any other source and use the
        - Responsible for data organisation and presentation
    * **Request**
        - for validation
* **Service Layer**
    * Services
        * All application and business logic goes here
        * Accessed through interfaces
        * Shared between different controllers
    * DTO
        * Data Transfer Objects
        * Used to encapsulate data going to and from services - to split from requests
        * Not always used
* **Repository Layer**
    * Repositories
        * these access the data model and perform operations
        * Shares how to access and manage data between controllers
        * Accessed through interfaces
        * Contains generic methods as well as model specific methods
        * Abstracts Eloquent to a certain extent out of the controller
* **Model**
    * Domain information
    * Eloquent
