
![](logo/cell6_logo.png)

cell6 is php microframork that I made for fun and for my personal projects :)

# Installation

```bash
git clone https://github.com/rostislavjadavan/cell6.git
```
Then just go to _http://localhost/cell6_ and everything should work.

If you have different installation path don't forget to update _RewriteBase_ in _.htaccess_ file.


# Folder structure

Whole application is located in _app_ directory.
- controllers
- core
- database
- views

Any other useful directory should be added here:
- libs
- models
- ...

# Configuration

Routes, configuration properties and database configuration is located in _app/config.php_.

## Routing

- Route to controller/method.
```php
$router->get('homepage', '/', 'Main::index');
```
Create route with name _homepage_ that execute _index_ method in controller _Main_ located in _app/controllers_ directory.

- Route to view
```php
$router->get('view', '/view', 'page');
```
Display view file __page.php__ located in __app/views__.

- Closure

```php
$router->get('closure', '/func', function () {  
    return "Hello!";
});
```

## Routing parameters

```php
$router->get('showName', '/show-name/<name>', 'Main::showName');
```

Controller:
```php
public function showName($name) {
    return $this->html("Your name is: $name");
}
```

## REST route

```php
$router->rest("user", '/api/user', 'UserApi');
```
Create route called _user_ that will execute controller _app/controllers/UserApi.php_.
Based on http verb method will be executed (get, post, put, delete):

```php
class UserApi extends RESTController {

    public function get() {
        return $this->json(['method' => 'GET']);
    }

    public function post() {
        return $this->json(['method' => 'POST']);
    }
}
```
If method is not implemented 501 error is returned.

## Error routes

```php
$router->error404('Main::error404');
$router->error500('Main::error500');
```

## Configuration properties

Configuration values can be stored in _Config_ class. Class is available in controller.

```php
class Main extends Controller {
    public function index() {
        $value = $this->config['key'];
        ...
    }
}
```

_app/config.php_
```php
$config['key'] = "value";
```

# Controller

```php
class Main extends Controller {

    public function index() {
        return $this->html("<h1>Hello world!");
    }
}
```

## Rendering view

```php
class Main extends Controller {

    public function index() {
        return $this->view("page");
    }
}
```

## Rendering view using template

_template($viewName, $templateName, $params, $code)_ method can be used. Params will be propagated into view and template.
  
```php
class Main extends Controller {

    public function index() {
        return $this->template("page", "bootstrap4/template", ["title" => 'Homepage']);
    }
}
```

# Url

Url generator is _Router_ class.

In view _Router_ is available as _$router_.

```php
<ul>
    <li><a href="<?php echo $router->url("page1") ?>">/page1</a></li>
    <li><a href="<?php echo $router->url("page2", ['name' => 'Rob']) ?>">/page2 with name</a></li>
</ul>
```

In controller url generator is available as: 

```php
class Main extends Controller {

    public function index() {
        $url = $this->url('route_name', ['param' => 1]);
        ...
    }
}
```

## Base Url

Base url is available in request:

```php
$request->getBaseUrl();
```

In view there is placeholder:

```php
{BASEURL}
```

Also there is placeholder that leads to _public_ directory:

```php
{PUBURL}
```

# Dependency injection

There is simple DI container available that is able to construct object graph using constructor injection.

Example of custom class that needs request object:
```php
class MyClass {
    public function __construct(\Core\Request $request) {
    }
}
```

```php
$myclass = $container->make('MyClass');
```

This will create _MyClass_ instance and inject request object.

If you need to make your class singleton so every class that will need your class will get same instance as dependency:

```php
$myclass = $container->singleton('MyClass');
```
Because of simplicity container does not support naming of singletons created into container. If you need another singleton 
instance of same class you can create another class extending the original class.

```php
class Database {
}

class SecondaryDatabase extends Database {
}

$container->singleton('Database');
$container->singleton('SecondaryDatabase');

class INeedBothDatabases {
    public function __construct(Database $database, SecondaryDatabase $secondaryDatabase) {
    }
}
```

If you need container in your class just add dependency into constructor:

```php
class MyClass {
    public function __construct(\Core\Container $container, \Core\Request $request) {
    }
}
```

# Request

```php
class Main extends Controller {

    public function postAction() {
        $allPostValues = $this->request->getPost();
        $particularPostValue = $this->request->getPost('key');

        $cookie = $this->request->getCookie('mycookie');
        
        return $this->template("page", "bootstrap4/template", ["title" => 'Homepage']);
    }
}
```

# Cookies

```php
class Main extends Controller {
    public function page() {
        $response = $this->html("html output");
        $response->setCookie("test", "value1");
        return $response;
    }
}
```
# Database

For database access cell6 is using Sparrow Database toolkit. See https://github.com/mikecao/sparrow.

There is Sqlite database located in _app/database_ folder that can be used. But you are free to use any
PDO compatible database.

```php
class Main extends Controller {
    public function showUser($id) {
        $database = $this->container->make('\Core\Database');
        
        $user = $database->from('user')->where('id', $id)->select(array('id', 'name'))->one();
        
        if ($user) {
            return $this->html("user: ".$user['name']);
        }        
        return $this->html("user not found", 404);
    }
}
```

## Database model and Container

You can use constructor injection in your database model.

```php
class ArticleModel {
    static $table = 'articles';
    
    public $id;
    public $date;
    public $title;
    public $content;
    
    private $router = null;
    private $config = null;
    
    public function __construct(Router $router, Config $config) {            
        $this->router = $router;
        $this->config = $config;
    }
    
    public function getUrl() {
        return $this->router->url('article', ['id' => $this->id]);
    }
    
}
```
