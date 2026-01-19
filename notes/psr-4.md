## PSR-4 = Namespace + Автозагрузка + Соответствие структуре папок

**PSR-4** — это стандарт, который описывает **как namespace должны соответствовать файловой структуре** для автозагрузки.

---

## Три составляющие PSR-4:

### 1. ✅ Использование namespace
```php
namespace App\Controllers;

class HomeController {
    // ...
}
```


### 2. ✅ Соответствие namespace структуре папок
```
Namespace: App\Controllers\HomeController
           ↓   ↓          ↓
Файл:      App/Controllers/HomeController.php
```


### 3. ✅ Автозагрузчик, который преобразует namespace в путь
```php
spl_autoload_register(function ($className) {
    // App\Controllers\HomeController → App/Controllers/HomeController.php
    $path = str_replace('\\', '/', $className) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});
```


---

## Сравнение подходов

### ❌ Без namespace (ваш текущий код)
```php
// Framework/Router.php
class Router { }

// index.php
$router = new Router();  // Простое имя класса

// Автозагрузчик
spl_autoload_register(function ($className) {
    // Router → Framework/Router.php (фиксированная папка)
    $path = basePath('Framework/' . $className . '.php');
    require $path;
});
```


### ✅ С PSR-4
```php
// Framework/Router.php
namespace Framework;  // ← Добавили namespace

class Router { }

// index.php
use Framework\Router;
$router = new Router();

// Автозагрузчик PSR-4
spl_autoload_register(function ($className) {
    // Framework\Router → Framework/Router.php (автоматически из namespace!)
    $path = str_replace('\\', '/', $className) . '.php';
    require $path;
});
```


---

## Главное отличие PSR-4

| Аспект | Ваш подход | PSR-4 |
|--------|------------|-------|
| **Namespace** | Нет | Обязательно |
| **Структура папок** | Фиксированная (`Framework/`) | Соответствует namespace |
| **Автозагрузчик** | Жёсткий путь | Преобразует `\` → `/` |
| **Гибкость** | Только одна папка | Любая структура |

---

## Пример: почему PSR-4 гибче

**Ваш автозагрузчик:**
```php
// Работает только для Framework/
$router = new Router();  // ищет Framework/Router.php
$db = new Database();    // ищет Framework/Database.php

// НЕ работает для других папок:
$controller = new HomeController();  // ❌ не найдёт в App/controllers/
```


**PSR-4:**
```php
// Работает для ЛЮБЫХ папок, соответствующих namespace:
$router = new Framework\Router();              // Framework/Router.php
$db = new Framework\Database();                // Framework/Database.php
$controller = new App\Controllers\HomeController(); // App/Controllers/HomeController.php
$model = new App\Models\User();                // App/Models/User.php
```


---

## Коротко

**PSR-4** — это не просто namespace, а **правило**:

> **Namespace класса = путь к файлу класса**

Обратные слэши `\` в namespace преобразуются в слэши `/` в пути к файлу.
