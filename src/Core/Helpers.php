<?php

declare(strict_types=1);

function csrf(): string
{
    $token = $_SESSION['csrf'] ?? '';
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars($token) . '">';
}

function old_error(string $field): string
{
    static $errors = null;
    if ($errors === null) {
        $errors = $_SESSION['validation_errors'] ?? [];
        unset($_SESSION['validation_errors']);
    }
    return $errors[$field][0]['message'] ?? '';
}

function old(string $field): string
{
    static $input = null;
    if ($input === null) {
        $input = $_SESSION['old_input'] ?? [];
        unset($_SESSION['old_input']);
    }
    return htmlspecialchars($input[$field] ?? '');
}

function flash(string $key): mixed
{
    $value = $_SESSION[$key] ?? null;
    unset($_SESSION[$key]);
    return $value;
}
