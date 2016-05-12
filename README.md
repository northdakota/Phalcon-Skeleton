# Phalcon-Skeleton

### Оглавление

1. Структура
 + [Backend + Frontend](#BaF);
 + [Настройка модулей](#MSettings);
 + [Роутер](#Route);
2. Контроллеры
 + [inprogress](#Parag);
 + [inprogress](#Headers);
3. Модели
 + [inprogress](#Links);
 + [inprogress](#Emphasis);
4. Шаблоны
 + [Шаблоны + Volt](#ShablonsVolt);
 + Основные операторы
 + Теги
 + Форма
 + Новые функции
5. Валидация
 + Форма (не забыть пути)
 + Класс валидации
 + Валидация в моделях
6. Добавление библиотек
 +
 +

7. Запросы
 + Магические методы
 + Билдер
8. Пагинация

9. Различные тонкости
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

    $di->set('db', function() {
    			$config = new ConfigIni("config/config.ini");

    			return new Database($config->database->toArray());
    		});
    		$di->set('session', function () {
    			$session = new SessionAdapter();
    			$session->start();
    			return $session;
    		});

    		$di->set('flash', function () {
    			return new FlashDirect();
    		});

    		$di->set('mail',function(){
    			$config = new ConfigIni("config/config.ini");
    			return $config->mail->toArray();
    		});

### <a name="Route"></a>Роутер
## Контроллеры
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