# LARAVEL STORE (E-COMMERCE)

 > This is a working in progress project, i will update this with more functionalities

This project was inspired by a selection process in which I participated. The goal of this project is to show my Laravel, PHP, Cloud and Backend knowledge.

## Project details

Develop a web application that demonstrates my ability to create easy and well-structured solutions using Laravel 10, MySQL, MongoDB and Vue.js.

## Technical requirements

**Backend:** Developed with Laravel 10, using MySQL for relational data and MongoDB for non-relational data.

**Frontend:** Some functionality will be implemented using Vue.js to create an interactive client-side experience.

**Authentication and Authorization:** Implement login/logout and access restriction functionalities based on different user types (e.g. administrator and regular user).

**Code Quality:** Clarity, maintenance, and organization of the code, following the SOLID and DRY principles.

**Project Structure:** Organization of files and folders, clear and descriptive class and method names.

**Security:** Implementation of security measures, such as protection against CSRF, XSS, and SQL injection.

## Future implementations

- CI/CD pipeline for continuous delivery and deployment;

- Virtual environment using a Kubernetes cluster to make my app available in the cloud and optimize development speed;

- Integration with a payment gateway;

- Store front developed in Vue.js

- Analytics with statistics on best-selling and most viewed products

## How to run this project

> Make sure you have docker installed

Clone this repo to your machine:

``` git clone https://github.com/JorgeRinaldi1995/laravel-app.git ```

Enter in laravel-app folder:

``` cd laravel-app ```

Create the .env file:

``` cp .env.dev .env ```

Get the project containers up:

``` docker-compose up -d ```

Access the container:

``` docker-compose exec app bash ```

Install project dependencies:

``` composer install ```

Generate the Laravel project key:

``` php artisan key:generate ```

Run the database migrations:

``` php artisan migrate ```

Access the project on localhost:8000: