<?php

declare(strict_types=1);

/**
 * Helper functions for the application
 */

/**
 * Get the base path
 *
 * @param string $path
 * @return string
 */
function basePath(string $path = ''): string
{
    return __DIR__ . '/' . $path;
}