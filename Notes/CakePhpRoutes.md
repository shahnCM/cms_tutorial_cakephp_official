In this chapter, we are going to learn the following topics related to routing −

*   Introduction to Routing
*   Connecting Routes
*   Passing Arguments to Routes
*   Generating urls
*   Redirect urls

### Introduction to Routing

In this section, we will see how you can implement routes, how you can pass arguments from URL to controller’s action, how you can generate URLs, and how you can redirect to a specific URL. Normally, routes are implemented in file config/routes.php. Routing can be implemented in two ways −

*   Static Method
*   Scoped Route Builder

_Both the methods will execute the index method of ArticlesController. Out of the two methods, ___scoped route builder___ gives better performance._

Example:

*   Using Static Method : `Router::connect()` method is used to connect routes. The following is the syntax of the method,

```php
Router::connect('/', ['controller' => 'Articles', 'action' => 'index']);
```

*   Using Scope Route Builder

```php
Router::scope('/', function ($routes) {
   $routes->connect('/', ['controller' => 'Articles', 'action' => 'index']);
});
```

If we use Scoped Routes we can attach middleware and also add other functionalities.

There are 3 arguments `$routes->connect()` & `Router::connect()` take,

*   The first argument is for the URL template you wish to match.
*   The second argument contains default values for your route elements.
*   The third argument contains options for the route, which generally
    contains regular expression rules.

```php
Router::scope('/', function ($routes) {
    $routes->connect(
        'URL template',
        ['default' => 'defaultValue'],
        ['option' => 'matchingRegex']
    );
});
```

### Implemantation:

We use RouteBuilder which gives us more control, in `config/routes.php` 

```php
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {
    
    // Register scoped middleware for in scopes.
    $builder->registerMiddleware(
        'csrf', 
        new CsrfProtectionMiddleware(['httpOnly' => true,]));

    // Apply the middleware
    $builder->applyMiddleware('csrf');
   
    /** 
     * Define Our Routes
     */

    $builder->connect(
        '/', 
        ['controller' => 'Tests', 
        'action' => 'show']
        );
    
    $builder->connect(
        '/pages/*', 
        ['controller' => 'Pages', 
        'action' => 'display']
        );
    
    $builder->fallbacks();
});
```

Now Let's create a `TestsController.php` file at `src/Controller/TestsController.php` and put the following code,

`src/Controller/TestsController.php`

```php
declare(strict_types=1);
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

class TestsController extends AppController {
   public function show()
   {

   }
}
```

Let's create a folder Tests under `src/Template` and under that folder, create a View file called `show.php`. Copy the following code in that file.

`src/Template/Tests/show.php`

```html
<h1>
    Rotues In Actions
</h1>
```

See what happens when you hit the URL.

### Passed Arguments

Passed arguments are teh arguments which are passed in the URL, These arguments can be passed to controller's action. These passed arguments are given to your controller in three ways. 

__As arguments to the action method__

Following example shows, how we can pass arguments to the action of the controller. Visit the following URL at `http://localhost/my-cake-prep/tests/value1/value2`

This will match the following route,

```php
$builder->connect(
    'tests/:arg1/:arg2', 
    [
        'controller' => 'Tests', 
        'action' => 'show'
    ],
    [
        'pass' => ['arg1', 'arg2']
    ]
);
```

We can get this arguments like,

```php
$args = $this->request->params[‘pass’]
```

We can also do it in following way, just pass the params after `action` key

```php
$builder->connect(
    'tests/:arg1/:arg2', 
    [
        'controller' => 'Tests', 
        'action' => 'show',
        'arg1',
        'arg2'
    ]
);
```

### Implementation:

In `config/rotes.php` 

```php
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {

    // Register scoped middleware for in scopes.
    $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true,
    ]));
    $builder->applyMiddleware('csrf');
    
    $builder->connect(
        'tests/:arg1/:arg2', 
        ['controller' => 'Tests', 'action' => 'show'],
        ['pass' => ['arg1', 'arg2']]
    );
    
    $builder->connect(
        '/pages/*', 
        ['controller' => 'Pages', 'action' => 'display']
    );
    
    $builder->fallbacks();
});
```

Create a `TestsController.php` file at 
`src/Controller/TestsController.php`. 
Let's have the following code in the controller file.

`src/Controller/TestsController.php`

```php
declare(strict_types=1);
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

class TestsController extends AppController {

    public function show($arg1, $arg2) {
        $this->set('argument1',$arg1);
        $this->set('argument2',$arg2);
    }
}

    // Or this way
    public function show() {
        $args = $this->request->params[‘pass’]
        $this->set('argument1',$args[0]);
        $this->set('argument2',$args[1]);
    }
}
```









