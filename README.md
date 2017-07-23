# Stratify HTTP stack

HTTP middleware utilities built upon:

- PSR-7 and [Zend Diactoros](https://github.com/zendframework/zend-diactoros) as the implementation
- [http-interop/http-middleware](https://github.com/http-interop/http-middleware) for middlewares

```
composer require stratify/http
```

## Middlewares

A middleware can be either an instance of `Interop\Http\ServerMiddleware\MiddlewareInterface`:

```php
class MyMiddleware implements \Interop\Http\ServerMiddleware\MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface
    {
        return new Response(...);
    }
}

$middleware = new MyMiddleware;
```

or a simple callable, which allows to use closures for quickly writing middlewares:

```php
$middleware = function(ServerRequestInterface $request, DelegateInterface $delegate) : ResponseInterface {
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
$response = $middleware->process($request, $delegate);
```

The pipe will first execute `Middleware1`. If that middleware calls `$next` then `Middleware2` will be executed. An infinite number of middlewares can be piped together.

If you don't need to use the `$delegate` argument for the pipe, you can use the `LastDelegate` class:

```php
$response = $middleware->process($request, new \Stratify\Http\Middleware\LastDelegate);
```
