# BisualMail for backetfy

## Instalación

```bash
composer require bisual/bisualmail

php artisan vendor:publish --provider="bisual\bisualMail\bisualMailServiceProvider"

php artisan migrate 
```

Añadir comando al scheduler de laravel en App\Console\Kernel.php
```php
protected function schedule(Schedule $schedule)
{

    ...
    $schedule->command('bisualmail:send-newsletter')->everyMinute();
    ...
}
```