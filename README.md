<p align="center">
  <a href="https://packagist.org/packages/supliu/laravel-query-monitor"><img src="https://poser.pugx.org/supliu/laravel-query-monitor/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/supliu/laravel-query-monitor"><img src="https://poser.pugx.org/supliu/laravel-query-monitor/v/stable.svg" alt="Latest Stable Version"></a>
</p>

# Laravel Quey Monitor

Library to monitoring Queries in real-time using Laravel.

## How to install

Use composer to install this package

```
composer require supliu/laravel-query-monitor
```

From Laravel 5.5 onwards, it's possible to take advantage of auto-discovery of the service provider. For Laravel versions before 5.5, you must register the service provider in your `config/app.php`

```php
'providers' => [
    /*
     * Package Service Providers...
     */
    \Supliu\LaravelQueryMonitor\ServiceProvider::class,
],
```

## How to use

Open you terminal and execute:

```php
php artisan laravel-query-monitor
```

Now just perform some action in your application that performs some interaction with the database.

## License

The Laravel Query Monitor is open-sourced project licensed under the [MIT license](https://opensource.org/licenses/MIT).