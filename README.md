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
    Latency     3.07ms    4.27ms  92.46ms   92.61%
    Req/Sec     2.56k   801.21    17.84k    67.79%
  1221250 requests in 30.10s, 203.82MB read
Requests/sec:  40575.11
Transfer/sec:      6.77MB
```

## Support

If you have any problems, do not heasitate to open an Issue.
