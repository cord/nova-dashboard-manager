Work in progress.

#### Installation:

Add the package using composer

`composer require marispro/nova-dashboard`

Run Migrations

`php artisan migrate`

Used packages:
- nova-bi/nova-databoards
- digital-creative/nova-dashboard


use Marispro\NovaDashboard\DashboardManager;

Add to the tools()-method in your NovaServiceProvider.php like this:
```php
public function tools()
{
    return [
        new DashboardManager(),
        new NovaDataboards(),
    ];
}
```
