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

`setNamespace($namespace = null)` Set the namespace, useful where you wish to work with multiple navigations systems.

`setOrder($column = 'order', $order = 'desc')` Sets the default ordering.

`findTypes(array $types)` Find index entries of a certain type, by a key/value pair array.

Keys can be 'disabled', 'navigation', 'published'.

Values can be null, true or false, and are set to null by default.

The following example will pull indices where 'navigation' is set
to false and 'published' is set to true only, it will ignore disabled.

```
findTypes([
    'disabled' => null,
    'navigation' => false,
    'published' => true,
]);
```

`findAndNestTypes(array $types)` Find index entries by a key/value pair array, and return a nested array where there are sub indices.
