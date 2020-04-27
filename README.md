<p align="center">
  <a href="https://packagist.org/packages/supliu/laravel-query-monitor"><img src="https://poser.pugx.org/supliu/laravel-query-monitor/d/total.svg" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/supliu/laravel-query-monitor"><img src="https://poser.pugx.org/supliu/laravel-query-monitor/v/stable.svg" alt="Latest Stable Version"></a>
</p>

# Laravel Query Monitor

A <a href="https://supliu.com.br">Supliu</a> Laravel Query Monitor is library to monitoring Queries in real-time using Laravel Artisan Command.

Basically it opens a socket listening and displays (on terminal) the queries executed in your Laravel application.

## How to install

Use composer to install this package

```
composer require supliu/laravel-query-monitor
```

## How to use

Open you terminal and execute:

```php
php artisan laravel-query-monitor
```

Now just perform some action in your application that performs some interaction with the database.

## License

The Laravel Query Monitor is open-sourced project licensed under the [MIT license](https://opensource.org/licenses/MIT).
