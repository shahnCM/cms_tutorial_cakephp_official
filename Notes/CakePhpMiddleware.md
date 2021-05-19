### Applying Middleware to all __Requests__

To apply middleware to all requests, use the `middleware` method of your `App\Application` class. Your application’s `middleware` ___hook method___ will be called at the beginning of the request process, you can use the `MiddlewareQueue object` to attach middleware:

in `App\Application`

```php
namespace App;

use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Error\Middleware\ErrorHandlerMiddleware;

class Application extends BaseApplication
{
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Bind the error handler into the middleware queue.
        $middlewareQueue->add(new ErrorHandlerMiddleware());
        
        // Here we can add our Middlewares

        return $middlewareQueue;
    }
}
```

In addition to adding to the end of the `MiddlewareQueue` you can do a variety of operations:

```php
// Instantiate the object of the middleware class
$layer = new \App\Middleware\CustomMiddleware;

/**
 * Various ways we can attach our middlewares 
 * in Diffrent Positions
 */

// Added middleware will be last in line.
$middlewareQueue->add($layer);

// Prepended middleware will be first in line.
$middlewareQueue->prepend($layer);

// Insert in a specific slot. If the slot is out of
// bounds, it will be added to the end.
$middlewareQueue->insertAt(2, $layer);

// Insert before another middleware.
// If the named class cannot be found,
// an exception will be raised.
$middlewareQueue->insertBefore(
    'Cake\Error\Middleware\ErrorHandlerMiddleware',
    $layer
);

// Insert after another middleware.
// If the named class cannot be found, the
// middleware will added to the end.
$middlewareQueue->insertAfter(
    'Cake\Error\Middleware\ErrorHandlerMiddleware',
    $layer
);
```

### Adding Middleware from Plugins

Plugins can use their `middleware` hook method to apply any middleware they have to the application’s middleware queue:

in `plugins/ContactManager/src/Plugin.php`

```php
namespace ContactManager;

use Cake\Core\BasePlugin;
use Cake\Http\MiddlewareQueue;
use ContactManager\Middleware\ContactManagerContextMiddleware;

class Plugin extends BasePlugin
{
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue->add(new ContactManagerContextMiddleware());

        return $middlewareQueue;
    }
}
```

### Creating Middleware

Middleware can either be implemented as anonymous functions (Closures), or classes which extend `Psr\Http\Server\MiddlewareInterface.` While Closures are suitable for smaller tasks they make testing harder, and can create a complicated `Application` class. Middleware classes in CakePHP have a few conventions:

*   Middleware class files should be put in src/Middleware. For example: src/
    Middleware/CorsMiddleware.php
*   Middleware classes should be suffixed with Middleware. For example:
    LinkMiddleware.
*   Middleware must implement Psr\Http\Server\MiddlewareInterface.

Middleware can return a response either by calling $handler->handle() or by creating their own response. We can see both options in our simple middleware:

In `src/Middleware/TrackingCookieMiddleware.php`

```php
namespace App\Middleware;

use Cake\Http\Cookie\Cookie;
use Cake\I18n\Time;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class TrackingCookieMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface
    {
        // Calling $handler->handle() delegates control to the *next* middleware
        // In your application's queue.
        $response = $handler->handle($request);

        if (!$request->getCookie('landing_page')) {
            $expiry = new Time('+ 1 year');
            $response = $response->withCookie(new Cookie(
                'landing_page',
                $request->getRequestTarget(),
                $expiry
            ));
        }

        return $response;
    }
}
```

Now that we’ve made a very simple middleware, let’s attach it to our application:

In `src/Application.php`

```php
namespace App;

use App\Middleware\TrackingCookieMiddleware;
use Cake\Http\MiddlewareQueue;

class Application
{
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        // Add your simple middleware onto the queue
        $middlewareQueue->add(new TrackingCookieMiddleware());

        // Add some more middleware onto the queue

        return $middlewareQueue;
    }
}
```

### Applying Middleware to Specific Routes

If you want to apply middleware to Specific Routes we can do it From the Route Scope & that is showed in the `CakePhpRotues.md`


