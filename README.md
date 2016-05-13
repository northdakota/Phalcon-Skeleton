# Phalcon-Skeleton

### Оглавление

1. Структура
 + [Backend + Frontend](#BaF)
 + [Настройка модулей](#MSettings)
 + [Роутер](#Route)
2. Контроллеры
 + [Общий принцип построения](#BaseC);
 + [Методы Action](#Action);
 + [Редиректы](#Redirects);
3. Модели
 + [Общий принцип построения](#BaseM);
 + [Связи](#Connections);
 + [Пустые строки](#Empty);
 + [Валидация(см. Валидация в моделях)](#Connections);
4. Запросы
  + Модели(Магические методы)
  + Билдер
5. Валидация
 + Форма (не забыть пути)
 + Класс валидации
 + Валидация в моделях
6. Шаблоны
 + [Шаблоны + Volt](#ShablonsVolt);
 + Основные операторы
 + Теги
 + Форма
 + Новые функции
7. Добавление библиотек
 +
 +
8. Пагинация
9. Локализация
10. Различные тонкости
  + Редирект

## Структура
### <a name="BaF"></a>Backend + Frontend

В данном скелете присутсвует двухмодульная структура формирования сайта - для администратора и обычного пользователя.
Каждый модуль имеет свои настройки, модели, структуру шаблонов и плагинов, конфигурацию. Модули имеют общую библиотеку.
Расположение модулей


* apps/<имя модуля>/

    * config - файлы конфигурации [пример](./apps/backend/config/config.ini)
    * controllers - классы контроллеров
    * form - классы форм
    * models - модели таблиц БД
    * plugins - плагины
    * validator - классы валидации
    * views - шаблоны
    Module.php - файл модуля

### <a name="MSettings"></a>Настройка модулей

Файлы [Module.php](./apps/backend/Module.php) отвечают за поведение модуля.

В функции registerAutoloaders указываем все папки с подключаемыми модулями. Если забудем это сделать, то не будет работать подклоючение модуля через use.
В registerServices($di) регистрируем сервисы через раздатчик.
Пример 1:
```
$di->set('flash', function () {
    return new FlashDirect();
});
```

Теперь мы можем использовать Flash сообщения в контроллерах.

```$this->flashSession->success("New password has being send on your email");```

```$this->flashSession->error('Email not found');```

Пример 2:
```
    $di->set('config',function(){
        $config = new ConfigIni("config/config.ini");
        return $config->api->toArray();
    });
```

Теперь мы можем получить конфиг для Api

```
     $di = DI::getDefault();
     $this->_config = $di->get("config");
```

### <a name="Route"></a>Роутер
За роутинг отвечает файл [./public/index.php](./public/index.php)

Формат роута:
```
$router->add("/:controller/:action/:params", array(
    'module' => 'frontend',
    'controller' => 1,
    'action' => 2,
    'params'=>3
));
```

## Контроллеры
### <a name="BaseC"></a>Общий принцип построения
Контроллер может быть наследником класса Controller. В нашем случае большинство контроллеров наследуют промежуточный класс ControllerBase, в котором реализованны наиболее часто встречающиеся функкции и проверки. К примеру проверка авторизации пользователя и функция редиректа.
В таком случае синтаксис контроллера:
```
namespace Multiple\Модуль(Backend/Frontend)\Controllers;

use Phalcon\Mvc\Controller;

class ИмяController extends (Controller или ControllerBase)
{

}
```
### <a name="Action"></a>Методы Action

## Модели
## Шаблоны
### <a name="ShablonsVolt"></a>Шаблоны + Volt
Есть два способа построения шаблонов
#### 1. Наследование от базового шаблона.
* [index.phtml](./apps/backend/views/index.phtml)
* [базовый шаблон](./apps/backend/views/base-template.phtml)
* [шаблон наследник](./apps/backend/views/admins/index.phtml)

В шаблоне наследнике указываете наследование от базового шаблона:

`{% extends "base-template.phtml" %}`

В наследнике переопределяем базовые блоки:

Достоинства:

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

* [index.phtml](./apps/frontend/views/index.phtml)

Достоинства:

1. Так привычней
2. Шаблон компилируется сразу


Недостатки:

1. Первый способ все равно правильней.


## Валидация
## Добавление библиотек
## Разны тонкости