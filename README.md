# Task Management
# SPA Authentication using Laravel 11 Sanctum, Vue 3 and Vite
## Prerequisites

Before you begin, ensure you have the following software installed:

- [PHP](https://www.php.net/downloads) (8.0 or higher)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/) (20 or higher)
- [NPM](https://www.npmjs.com/get-npm) (Node Package Manager, comes with Node.js)

## Installation

```bash
   https://github.com/proficient-pravin/task-management.git
   cd task-management
```

## Install PHP dependencies
```bash
composer install
```

## Copy the environment file

```bash
cp .env.example .env
```
## Generate the application key
```bash
php artisan key:generate
```
## Set up the SQLite database
```bash
touch database/database.sqlite
```
## Run migrations and serve project locally

```bash
php artisan migrate
npm install
npm run dev
php artisan serve
```
