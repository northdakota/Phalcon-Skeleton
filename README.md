# Phalcon-Skeleton

### Шаблоны + Volt
Есть два способа построения шаблонов
#### 1. Наследование от базового шаблона.
* [index.phtml](./apps/backend/views/index.phtml)
* [базовый шаблон](./apps/backend/views/base-template.phtml)
* [шаблон наследник](./apps/backend/views/admins/index.phtml)

В шаблоне наследнике указываете наследование от базового шаблона:

`{% extends "base-template.phtml" %}`

В наследнике переопределяем базовые блоки:



Достоиства:
1. Так правильней
2. Так удобней

Недостатки:
1. Изменения в базовом шаблоне не приводят к перекомпиляции шаблона наследника. Volt внесет изменения только после изменения в шаблоне наследнике или после удаления откомпилированных файлов.

Это можно исправить, если включить принудительную компиляцию файле Module.php в public function registerServices(DiInterface $di).

```
$di->set('view', function() {
    $view = new View();
    $view->setViewsDir('../apps/backend/views/');
    $view->registerEngines(
        array(
            ".phtml" => //'Phalcon\Mvc\View\Engine\Volt'
                function($view, $di){
                    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
                    $volt->setOptions(array(
                        'compileAlways' => true
                        //"true" if you need to renew children templates after base template changed
                        //or remove or compiled files or make changes in children
                    ));
                    return $volt;
                }
        )
    );
    return $view;
});
```

#### 2. Включение в базовый шаблон.

[Read more words!](./.gitignore)

