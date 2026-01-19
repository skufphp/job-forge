## Что такое Namespace?

**Namespace** (пространство имён) — это способ организации кода, чтобы избежать конфликтов имён классов.

### Проблема без namespace

Представьте два класса с одинаковым именем:

```php
// файл: Framework/Router.php
class Router {
    // ваш роутер
}

// файл: Vendor/SomeLibrary/Router.php
class Router {
    // роутер из библиотеки
}

// Какой Router использовать? Конфликт! ❌
$router = new Router();
```


### Решение: Namespace

```php
// файл: Framework/Router.php
namespace Framework;

class Router {
    // ваш роутер
}

// файл: Vendor/SomeLibrary/Router.php
namespace Vendor\SomeLibrary;

class Router {
    // роутер из библиотеки
}

// Теперь это разные классы! ✅
$myRouter = new Framework\Router();
$libRouter = new Vendor\SomeLibrary\Router();
```


---

## Алгоритм работы Namespace

### 1. Объявление namespace (в начале файла)

```php
<?php
namespace App\Controllers;  // ← Объявляем namespace

class HomeController {
    // Полное имя этого класса: App\Controllers\HomeController
}
```


### 2. Использование классов из других namespace

**Вариант А: Полное имя (FQCN - Fully Qualified Class Name)**
```php
$controller = new \App\Controllers\HomeController();
//                  ↑ начинается с \ = абсолютный путь
```


**Вариант Б: Импорт с помощью `use`**
```php
use App\Controllers\HomeController;

$controller = new HomeController();  // Теперь можно использовать короткое имя
```


**Вариант В: Импорт с алиасом**
```php
use App\Controllers\HomeController as MyController;

$controller = new MyController();
```


---

## Структура namespace в вашем проекте

Посмотрим на вашу структуру папок:
```
job-forge/
├── App/
│   ├── controllers/
│   └── views/
├── Framework/
│   ├── Database.php
│   └── Router.php
└── public/
```


### Логичные namespace для вашего проекта:

```php
// Framework/Router.php
namespace Framework;

class Router {
    // Полное имя: Framework\Router
}
```


```php
// Framework/Database.php
namespace Framework;

class Database {
    // Полное имя: Framework\Database
}
```


```php
// App/controllers/HomeController.php
namespace App\Controllers;

class HomeController {
    // Полное имя: App\Controllers\HomeController
}
```


---

## Примеры использования

### Без импорта (полные имена)
```php
$router = new \Framework\Router();
$db = new \Framework\Database();
$controller = new \App\Controllers\HomeController();
```


### С импортом (короткие имена)
```php
use Framework\Router;
use Framework\Database;
use App\Controllers\HomeController;

$router = new Router();
$db = new Database();
$controller = new HomeController();
```


---

## Правила namespace

1. **Объявление:** Первая инструкция после `<?php` (кроме `declare`)
```php
<?php
   namespace App\Controllers;  // ← Сразу после <?php
```


2. **Вложенность:** Разделяется обратным слэшем `\`
```php
namespace Level1\Level2\Level3;
```


3. **Один namespace = один файл** (стандарт PSR-4)

4. **Соответствие структуре папок** (рекомендуется для автозагрузки)
```
Namespace: App\Controllers\HomeController
   Файл:      App/Controllers/HomeController.php
```


---

## Namespace в вашем текущем коде

Сейчас у вас **НЕТ namespace**:

```php
// Framework/Router.php
class Router {  // ← Нет namespace = глобальное пространство имён
}

// Framework/Database.php
class Database {  // ← Нет namespace = глобальное пространство имён
}
```


Ваш простой автозагрузчик работает **без namespace**, поэтому вы используете классы напрямую:
```php
$router = new Router();  // Просто Router, без префикса
```


---

## Почему `App` часто используется?

- **Composer** (менеджер зависимостей PHP) рекомендует использовать `App` для кода вашего приложения
- **Стандарт PSR-4** предлагает такую структуру:
    - `App\` — ваш код
    - `Framework\` — ваш фреймворк
    - `Vendor\PackageName\` — сторонние библиотеки

Но это **не обязательно**! Вы можете использовать любое имя:
```php
namespace MyProject\Core;
namespace Blog\Models;
namespace Store\Payments;
```


---

**Главное:** Namespace — это просто **метка**, которая делает имя класса уникальным. Это не имеет отношения к файловой системе, пока вы не используете PSR-4 автозагрузку.
