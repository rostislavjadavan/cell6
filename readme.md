# cell6
## php mvc microframework

## Folder structure

Whole application is located in _app_ directory.
- controllers
- core
- database
- views

## Configuration

Routes, properties and database configuration is located in _app/config.php_.

### Routing

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

### Routing parameters

```php
$router->get('showName', '/show-name/<name>', 'Main::showName');
```

Controller:
```php
public function showName($name) {
    return $this->html("Your name is: $name");
}
```

### REST route

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

### Error routes

```php
$router->error404('Main::error404');
$router->error500('Main::error500');
```

## Controller

```php
class Main extends Controller {

    public function index() {
        return $this->html("<h1>Hello world!");
    }
}
```

### Rendering view

```php
class Main extends Controller {

    public function index() {
        return $this->view("page");
    }
}
```

### Rendering view using template

_template($viewName, $templateName, $params, $code)_ method can be used. Params will be propagated into view and template.
  
```php
class Main extends Controller {

    public function index() {
        return $this->template("page", "bootstrap4/template", ["title" => 'Homepage']);
    }
}
```

## Url

Url generator is _Router_ class.

Im view _Router_ is available as _$router_.

```php
<ul>
    <li><a href="<?php echo $router->url("page1") ?>">/page1</a></li>
    <li><a href="<?php echo $router->url("page2", ['name' => 'Rob']) ?>">/page2 with name</a></li>
</ul>
```
### Base Url

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

## Dependency injenction

There is simple DI container available that is able to construct object graph using constructor injection.

Example of custom class that needs request object:
```php
class MyClass {
    public function __construct(\Core\Request $request) {
    }
}
```

```php
$mmyclass = $container->make('MyClass');
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

## Request

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

## Cookies

```php
class Main extends Controller {
    public function page() {
        $response = $this->html("html output");
        $response->setCookie("test", "value1");
        return $response;
    }
}
```
## Database

For database access cell6 is using Sparrow Database toolkit. See https://github.com/mikecao/sparrow.

There is Sqlite database located in _app/database_ folder that can be used. But you are feel free to use any
PDO compatible database.


