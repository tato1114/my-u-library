## About this project

Hi, this project is created using 
 - Laravel 10
 - PHP 8.1.2 and
 - Postgresql 14.8

and it was made on Ubuntu 22.04 running over WSL2, so make sure to have at least a compatible version fo these dependencies.

## Installation

 - Run `composer install`
 - Run `cp .env.example .env`
 - Run `php artisan key:generate`
 - Configure your database credentials on `.env` file
 - Run `php artisan migrate --seed`, if the database is clean, otherwise use `php artisan migrate:fresh --seed`
 - There will be two default users:
    - Librarian: email: librarian@email.com, password: Password1$
    - User: email: user@email.com, password: Password1$
 - You can make an initial test running `php artisan test`
 - Or start dev server with `php artisan serve`

## Endpoints description

|Method          |Route                         |Name              |Description                                                             |Role allowed                           |
|----------------|------------------------------|------------------|------------------------------------------------------------------------|---------------------------------------|
|POST            | api/auth/login               |user.login        |User login                                                              |both                                   |
|POST            | api/auth/register            |user.register     |Allows to librarian user register ner users                             |librarian          	                |
|GET             | api/books                    |books.index       |Lists all the books                                                     |both                                   |
|POST            | api/books                    |books.store       |Stores a new book                                                       |librarian                              |
|GET             | api/books/{book}             |books.show        |Shows a book by its id                                                  |both                                   |
|PUT             | api/books/{book}             |books.update      |Updates a book                                                          |librarian                              |
|DELETE          | api/books/{book}             |books.destroy     |Deletes a book (soft delete)                                            |librarian                              |
|POST            | api/books/{book}/check_outs  |check_outs.store  |Stores a book check out                                                 |user                                   |
|GET             | api/check_outs               |check_outs.index  |Lists check outs (all for the librarian and only the normal user's own) |both (user with access restrictions)   |
|GET             | api/check_outs/{check_out}   |check_outs.show   |Shows a checkout in detail                                              |both (user with access restrictions)   |
|PUT             | api/check_outs/{check_out}   |check_outs.update |Closes a checkout                                                       |librarian                              |
