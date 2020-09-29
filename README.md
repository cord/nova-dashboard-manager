Work in progress.

#### Installation:

Add the package using composer

`composer require marispro/nova-dashboard-manager`

Run Migrations

`php artisan migrate`

Used packages:
- nova-bi/nova-dashboard-manager
- digital-creative/nova-dashboard
####
NovaServiceProvider.php add classes:
```php
use Marispro\NovaDashboardManager\DashboardManager;
use Marispro\NovaDashboardManager\NovaDataboards;
```
Add to the tools() methods like this:
```php
public function tools()
{
    return [
        new DashboardManager(), // must be loaded first !!!
        new NovaDataboards(),
    ];
}
```

**Recommended:** Publish Configuration File

    php artisan vendor:publish --provider="Marispro\NovaDashboardManager\DashboardManagerServiceProvider" --tag="config"


with `showToolMenu` you can configure if you want to use the Tool Menu default Resource Listing. Set to `false` when using with [Collapsible Resource Manager](https://novapackages.com/packages/digital-creative/collapsible-resource-manager).

    
    
    
**Optional:** Publish Migrations
    
    php artisan vendor:publish --provider="Marispro\NovaDashboardManager\DashboardManagerServiceProvider" --tag="migrations"
