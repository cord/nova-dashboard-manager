Work in progress.

#### Installation:

Add the package using composer

`composer require marispro/nova-dashboard-manager`

Run Migrations

`php artisan migrate`

Used packages:
- nova-bi/nova-databoards
- digital-creative/nova-dashboard
####
NovaServiceProvider.php add classes:
```php
use Marispro\NovaDashboardManager\DashboardManager;
use NovaBI\NovaDataboards\NovaDataboards;
```
Add to the tools() methods like this:
```php
public function tools()
{
    return [
        new DashboardManager(),
        new NovaDataboards(),
    ];
}
```
