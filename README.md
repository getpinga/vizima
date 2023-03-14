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

$app->get('/hello/{name}', function (Swoole\Http\Request $request, $name) {
    return "Hello $name";
});

$app->post('/user/create', function () {
    return json_encode(['code'=>0 ,'message' => 'ok']);
});

$app->start();
```

Run command ```php start.php```

Going to http://127.0.0.1:3000/hello/world will now display "Hello world".

## Benchmark

```
Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency     3.13ms    3.35ms  63.42ms   92.98%
    Req/Sec     2.23k   587.77     3.88k    67.05%
  1067163 requests in 30.08s, 178.10MB read
Requests/sec:  35479.09
Transfer/sec:      5.92MB

```
