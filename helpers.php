<?php

declare(strict_types=1);

/**
 * Helper functions for the application
 */

/**
 * Constructs the base directory path by appending the specified relative path.
 *
 * @param string $path The relative path to be appended to the base directory. Defaults to an empty string.
 * @return string The absolute path resulting from combining the base directory with the provided relative path.
 */
function basePath(string $path = ''): string
{
    return __DIR__ . '/' . $path;
}

/**
 * Load a view file by name.
 *
 * @param string $name The name of the view to load.
 * @return void
 */
function loadView(string $name): void
{
    $viewPath = basePath("views/$name.view.php");

    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        echo "View $name not found.";
    }
}

/**
 * Load a partial view file by name.
 *
 * @param string $name The name of the partial view to load.
 * @return void
 */
function loadPartials(string $name): void
{
    $partialPath = basePath("views/partials/$name.php");

    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "Partial view $name not found.";
    }
}

/**
 * Outputs a human-readable representation of the given value.
 *
 * @param mixed $value The value to be inspected and printed.
 * @return void
 */
function inspect(mixed $value): void
{
    echo '<pre>' . var_dump($value) . '</pre>';
}

/**
 * Outputs a human-readable representation of the given value and exits the script.
 *
 * @param mixed $value The value to be inspected and printed.
 * @return never
 */
function inspectAndExit(mixed $value): never
{
    echo '<pre>' . var_dump($value) . '</pre>';
    exit(1);
}