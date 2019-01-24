# Nova Page Tool.

This tool allows you to create and manage web pages.

## Installation

You can install the package via composer:

```bash
composer require digitalcloud/nova-page-tool
```

Next you need  to create the database table by running the migrations:

```shell
php artisan migrate
```

You can publish the required assets with:

php artisan vendor:publish --provider="DigitalCloud\PageBuilderField\FieldServiceProvider" --tag=public

## Usage

You must register the tool with Nova. This is typically done in the tools method of the NovaServiceProvider, in app/Providers/NovaServiceProvider.php.

```php

use DigitalCloud\PageTool\PageTool;
// ....

public function tools()
{
    return [
        // ...
        new PageTool(),
        // ...
    ];
}

```
