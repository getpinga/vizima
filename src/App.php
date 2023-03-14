<?php

namespace Vizima;

use Swoole\Http\Request;
use Swoole\Http\Response;
use FastRoute\Dispatcher;

class App
{
    /**
     * @var array
     */
    protected $routeInfo = [];

    /**
     * @var Dispatcher
     */
    protected $dispatcher = null;

    /**
     * @var string
     */
    protected $pathPrefix = '';

    /**
     * @param $ip
     * @param $port
     */
    public function __construct($ip = '0.0.0.0', $port = 8080)
    {
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @param $path
     * @param $callback
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function put($path, $callback)
    {
        $this->addRoute('PUT', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function patch($path, $callback)
    {
        $this->addRoute('PATCH', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function head($path, $callback)
    {
        $this->addRoute('HEAD', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function options($path, $callback)
    {
        $this->addRoute('OPTIONS', $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function any($path, $callback)
    {
        $this->addRoute(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS'], $path, $callback);
    }

    /**
     * @param $path
     * @param $callback
     */
    public function group($path, $callback)
    {
        $this->pathPrefix = $path;
        $callback($this);
        $this->pathPrefix = '';
    }

    /**
     * @param $method
     * @param $path
     * @param $callback
     */
    public function addRoute($method, $path, $callback)
    {
        $methods = (array)$method;
        foreach ($methods as $method) {
            $this->routeInfo[$method][] = [$this->pathPrefix . $path, $callback];
        }
    }

    /**
     * start
     */
    public function start()
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
            foreach ($this->routeInfo as $method => $callbacks) {
                foreach ($callbacks as $info) {
                    $r->addRoute($method, $info[0], $info[1]);
                }
            }
        });

        $http = new \Swoole\Http\Server($this->ip, $this->port);
        $http->on('request', [$this, 'onRequest']);
        $http->start();
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        static $callbacks = [];
        try {
            $path = $request->server['request_uri'];
            $method = $request->server['request_method'];
            $key = $method . $path;
            $callback = $callbacks[$key] ?? null;
            if ($callback) {
                $response->end($callback($request));
                return;
            }

            $ret = $this->dispatcher->dispatch($method, $path);
            if ($ret[0] === Dispatcher::FOUND) {
                $callback = $ret[1];
                if (!empty($ret[2])) {
                    $args = array_values($ret[2]);
                    $callback = function ($request) use ($args, $callback) {
                        return $callback($request, ... $args);
                    };
                }
                $callbacks[$key] = $callback;
                $response->end($callback($request));
            } else {
                $response->status(404);
                $response->end('<h1>404 Not Found</h1>');
            }
        } catch (\Throwable $e) {
            $response->status(500);
            $response->end((string)$e);
        }
    }
}
