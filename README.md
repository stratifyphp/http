# Stratify HTTP stack

HTTP middleware utilities built upon PSR-7 and [Zend Diactoros](https://github.com/zendframework/zend-diactoros) as the PSR-7 implementation.

```
composer require stratify/http
```

## Middleware

```php
interface Middleware
{
    public function __invoke(ServerRequestInterface $request, callable $next) : ResponseInterface;
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
$response = $middleware($request, $next);
```

The pipe will first execute `Middleware1`. If that middleware calls `$next` then `Middleware2` will be executed. An infinite number of middlewares can be piped together.
