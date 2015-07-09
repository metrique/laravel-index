# laravel-index

Create and manage a 2 tier navigation index in Laravel 5.

## Installation

1. Add the following to the `repositories` section of your composer.json

```
"repositories": [
    {
        "url": "https://github.com/Metrique/laravel-index",
        "type": "git"
    }
],
```

2. Add `"Metrique/laravel-index": "dev-master"` to the require section of your composer.json. 
3. `composer update`
4. Add `Metrique\Index\IndexServiceProvider::class,` to your list of service providers. in `config/app.php`.
5. `php artisan vendor:publish` to publish the `config/metrique-index.php` config file to your application config directory.
6. `php artisan metrique:index-migrations` to install the migrations to the database/migrations in your application.

## Usage

- To do.