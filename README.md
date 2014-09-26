Rester
======

基于[Slim](http://www.slimframework.com/) + [Laravel Eloquent](http://laravel.com/docs/4.2/eloquent)的RESTful API框架。

由于Laravel的验证依赖了很多Symfony的组件，所以我简化了一个版本([validation](https://github.com/overtrue/validation))，并引用到这个项目中。


#Usage

### 安装
```
git clone --recursive https://github.com/overtrue/rester
```
_*请一定要加`--recursive`哦*_, 不然你就需要composer update一下

### 路由：

app/routes.php

```
<?php

$app->get('/', function(){
    return json_encode(['hello' => 'world!']);
});

```
或者也可以使用控制器：

```
$app->get('/', 'HomeController:index'); //调用HomeController的index方法

```

_更多路由的使用请阅读Slim官方文档：http://docs.slimframework.com/#Routing-Overview_

### 控制器

```
<?php

/**
 * 演示控制器
 */
class HomeController extends Controller
{
    public function index() {
        return $this->json(['app' => 'Rester', 'message' => 'Hello world!']);
    }

    //...
}

```

控制器里有用的方法有：

- `$this->init()`  
会在项目初始化完成后首先调用，可以用来做一些初始化或者每个方法都需要用到的动作，比如用户授权。

- `$this->json(mixed $data [, int $status = 200])`  
输出`json`格式数据，第二个参数为状态码。

- `$this->jsonp(mixed $data [, string $callback = ''])`   
输出`jsonp`数据，第二个参数为回调函数名，可选，为空默认从$_GET读取`callback`, 如果最终获取不到callback，输出等于`$this->json`。

- `$this->validate(array $input, array $rules [, boolean $return = false])`    
数据验证，默认验证失败主动返回错误json输出并停止往下运行，当`$return = true`时不停止运行，返回验证失败消息。

- `$this->error(string $message, int $status [, array $errors = []])` 
输出错误json， `$errors` 为错误明细，通常为数组，可选。

_更多数据验证规则请参阅：http://laravel.com/docs/4.2/validation_

# License

MIT
