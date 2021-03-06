# Stratify HTTP stack

HTTP middleware utilities built upon:

- PSR-7 and [Diactoros](https://docs.laminas.dev/laminas-diactoros/) as the implementation
- [PSR-15](https://www.php-fig.org/psr/psr-15/)

```
composer require stratify/http
```

## Middlewares

A middleware can be either an instance of `Psr\Http\Server\MiddlewareInterface`:

```php
class MyMiddleware implements \Psr\Http\Server\MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        return new Response(...);
    }
}

$middleware = new MyMiddleware;
```

or a simple callable, which allows to use closures for quickly writing middlewares:

```php
$middleware = function(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface {
    return new Response(...);
}
```

## Middleware pipe

The middleware pipe let us pipe middlewares to execute one after the other. It is similar to using the pipe (|) operator on the command line.

It's interesting to note that the pipe *is also a middleware*, which means it can be nested or combined with any other middleware.

Usage:

```php
$middleware = new Pipe([
    new Middleware1,
    new Middleware2,
    // ...
]);

// Run
$response = $middleware->process($request, $handler);
```

The pipe will first execute `Middleware1`. If that middleware calls `$next` then `Middleware2` will be executed. An infinite number of middlewares can be piped together.

If you don't need to use the `$handler` argument for the pipe, you can use the `LastHandler` class:

```php
$response = $middleware->process($request, new \Stratify\Http\Middleware\LastHandler);
```
