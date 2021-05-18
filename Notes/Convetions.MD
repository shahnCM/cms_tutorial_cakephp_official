### Conventions Over Configuration

* CakePHP provides a basic organizational structure that covers class names, filename, database table names and other conventions.

* While the conventions take some time to learn, by following the conventions CakePHP provides, we can avoid needless configuration 
and make a uniform application structure that makes working with various project simple

* CakePHP Follows Models, Views and Controllers Concept which is also known as MVC Design Pattern in Application Development.

### Conventions:

* in the template folder we need a folder which has same name as the controller, and inside that folder files should be named accordingly controller method

```
Controller name:        'ArticlesController' having a method 'edit'
Template Folder name:   'Articles'
Template File name:     'Articles/edit.php'
```

### Models:

* The Modle Layer represents the part of your application that implements the business logic

* It is responsible for retriving data and converting it into the primary meaningful concepts in your application.

* This includes processing, validating, associating or other tasks related to handling data

* In the case of a social network, the Model layer would take care of tasks such as saving the user data, saving friends' association/relation, sorting and retriving user photos, finding suggestions for new friends' etc. The model objects can be thought of as "Friend", "User", "Comment" or "Photo"

### Views:

* The view layer renders a presentation of modelled data, Being seperate from the Model Objects, it is responsible for using the information it has available to produce any presentational interface your application might need.

* For example, the view could use model data to render an HTML view template containing it, or a XML formatted result for others to consume:

* The View layer is not only limited to HTML or text representation of the data. 

* It can be used to deliver common data formats like JSON, XML, and through a pluggable architecture any other format we may need, such as CSV

### Controllers:

* The Controller layer handles requests from users. It is responsible for rendering a response with the aid of both the Model and the View layers.

* A Controller can be seen as manager that ensures that all resourses needed for completing a task are delegated to the correct workers, It waits for petitions from clients, checks their validity according to authentication or authorization rules, presentational data that fetching or processing to the model, selects the type of presentational data that the clients are accepting and finally delegates the rendering process to the View Layer.

### CakePhp REQUEST LIFECYCLE:

1. The webserver rewrite rules direct the request to webroot/index.php

2. Your application is loaded and bound to an HttpServer.

3. Your application's middleware is initialized.

4. A request and response is dispatched through the PSR-7 Middleware that
application uses. (_Typically this includes error trapping and routing._)

5. If no response is returned from the middleware and the request contains routing information, a controller & action are selected.

6. The controller's action is called and the controller interects with the required Models and Components.

7. The controller delegates response creation to the View to generate teh output resulting from the model data.

8. The view uses Helpers and Cells to generate the response body and headers.

9. The response is sent back out through the Middleware

10. The HttpServer emits the response to the webserver

```
index.php 
|
|> autoloader 
    | 
    |> load application & bind to HttpServer 
        |
        |> Middleware 
            |
            |> controller <|Model <~~~***  
                |
                |> component
                |> View ---> (Helper + Cell)
                    |
                    |> Middleware
                        |
                        |> Response
``` 

### CakePHP Folder Structure

1.  The bin folder holdes the Cake console executables.

2.  The config folder holdes the Configuration files CakePHP uses.
    Database connection details, bootstraping, core configuration files and
    more should be stored here.

3.  The plugins folder is where the Plugins your application uses are stored

4.  The logs folder normally contains your log files, depending on log configuration.

5.  The src folder will be where your application's source files stay (Model, Controller)

6.  The templates folder has presentational files placed here: elements, error pages, layouts and view template files.

7.  The resources folder has sub folder for various types of resources files
    The locales sub folder stores language files for internationalization
8.  The tests folder will be where you put the test cases for your application.

9.  The tmp folder is where CakePHP stores temporary data, The actual dta it stores depends on how you have CakePHP configured, but this folder is usually used to store translation messages, model description and sometimes session information.

10. The vendor folder wait we all know what this do . :3

11. The web-root directory is the public document root of the application.
    It contains all the files we want publicly reachable.
