<?php

declare(strict_types=1);

namespace Src\Core;

class Storage
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = dirname(__DIR__, 2) . '/public/';
    }

    public function validar(array $file): bool
    {
        $extensoes = ['jpg', 'jpeg', 'png', 'webp'];
        $tamanhoMax = 5 * 1024 * 1024;
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (! in_array($extensao, $extensoes)) {
            return false;
        }

        if ($file['size'] > $tamanhoMax) {
            return false;
        }

        return true;
    }
    public function put(array $file, string $folder): string|false
    {
        $folder = trim($folder, '/');
        $targetDir = $this->basePath . $folder . '/';

        if (! is_dir($targetDir)) {
            if (! mkdir($targetDir, 0755, true)) {
                return false;
            }
        }

        $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
        $novoNome = bin2hex(random_bytes(12)) . '.' . $extensao;

        $destinoFinal = $targetDir . $novoNome;

        if (move_uploaded_file($file['tmp_name'], $destinoFinal)) {
            return $folder . '/' . $novoNome;
        }

        return false;
    }

    public function delete(string $uri): bool
    {
        $caminhoFisico = $this->basePath . ltrim($uri, '/');

        if (file_exists($caminhoFisico) && is_file($caminhoFisico)) {
            return unlink($caminhoFisico);
        }

        return false;
    }
}
