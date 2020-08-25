Work in progress.

#### Installation:

Add the package using composer
`composer require marispro/nova-dashboard`
run Migrations
php artisan migrate

Used packages:
- nova-bi/nova-databoards
- digital-creative/nova-dashboard


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
