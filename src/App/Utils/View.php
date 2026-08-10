<?php

declare(strict_types=1);

namespace Src\App\Utils;

class View
{
    public static function render($view, $vars = [])
    {
        $file = __DIR__ . '/../../views/' . $view . '.php';

        if (! file_exists($file)) {
            return '';
        }

        extract($vars);

        ob_start();
        include $file;
        $content = ob_get_clean();

        foreach ($vars as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $content = str_replace('{{' . $key . '}}', (string) $value, $content);
            }
        }

        return $content;
    }
}
