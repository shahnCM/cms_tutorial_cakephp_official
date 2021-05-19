Cake Php Study / Understanding

## Controllers

Concepts of Controller is Simple and as usual.

Basic look of a controller it extends our base app controller `AppController`

```php
namespace App\Controller;

class ArticlesController extends AppController
{
}
```

And rest are as usual, there can be index method for which calling the controller is sufficient because we don't need to call the `index()` method of any `class` explicitly 

We can also define our own interface and force using those interface methods from within the class. 

Other than those everything is simple and self-explanatory

### REST api

For REST response (Json Response) 

The fastest way to get up and running with REST is to add a few lines to setup [resource routes](https://book.cakephp.org/4/en/development/routing.html#resource-routes) in your `config/routes.php` file.

Once the router has been set up to map REST requests to certain controller actions.

We need to `initialize RequestHandler`
no need to import it

```php
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }
```

We can set our response using the `set() method`
which takes an array, name of the data we want to send it as as the key and the data it self as value,
we can send multiple key-value pairs

We can also set ___response message___ from the `set() method`

For serializing our data to JSON formatt, we pass the string `'serialize'` in the method `setOption()`, 

```php
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $recipe = $this->Recipes->newEntity($this->request->getData());
        if ($this->Recipes->save($recipe)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'recipe' => $recipe,
        ]);
        $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
    }
```

There are othre ways to send response with preferred status code.

```php
$response = $this->response
                    ->withType('application/json')
                    ->withStatus(403)
                    ->withStringBody(json_encode(['Foo' => 'bar']));
return $response;
```

