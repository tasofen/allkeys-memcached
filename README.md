# Fix getAllKeys for php-memcached (read from socket)
[![Total Downloads](https://img.shields.io/packagist/dt/tasofen/allkeys-memcached.svg?colorB=brightgreen)](https://packagist.org/packages/tasofen/allkeys-memcached)

## Install via [composer](https://getcomposer.org/)
```bash
composer require tasofen/allkeys-memcached
```

## Use
```php
require 'vendor/autoload.php';
$m = new tasofen\FixMemcached();
$m->addServer('127.0.0.1', 11211);
$m->set('key-1', 'value-1');
$m->set('key-2', 'value-2');
print_r($m->getAllKeys());
```

