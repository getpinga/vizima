# Vizima
Vizima is a high-performance API micro-framework designed to help you create powerful APIs in PHP. This project is a port of the popular [Mark](https://github.com/passwalls/mark) framework to Swoole, providing even greater performance and scalability.

## Install

```
composer require pinga/vizima
```

## Usage

```php
# start.php

<?php

require 'vendor/autoload.php';

$app = new \Vizima\App('0.0.0.0', 8080);

$app->get('/', function() {
    return '<h1>Hello, world!</h1>';
});

$app->start();
```
