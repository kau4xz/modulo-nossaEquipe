<?php

declare(strict_types=1);

namespace Src\Core;

use InvalidArgumentException;
use RuntimeException;

class FileService
{
    private static array $allowedMimes = [
        'image/jpeg',
        'image/jpg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    private static int $maxFileSize = 10 * 1024 * 1024;

    private static string $basePath;
    private static string $baseUrl;

    public static function init(
        string $basePath,
        string $baseUrl,
        int $maxFileSize = 10 * 1024 * 1024
    ): void {
        self::$basePath = rtrim(str_replace('\\', '/', $basePath), '/');
        self::$baseUrl = rtrim($baseUrl, '/');
        self::$maxFileSize = $maxFileSize;
    }

    public static function save(array $file, string $folder): string
    {
        self::checkInit();

        $validated = self::validateFile($file);
        $mime = $validated['mime'];

        $folder = self::sanitizePathSegment($folder);
        $directory = self::$basePath . "/{$folder}";

        if (! is_dir($directory) && ! mkdir($directory, 0755, true)) {
            throw new RuntimeException("Não foi possível criar o diretório: {$directory}");
        }

        // Nome aleatório
        $finalName = bin2hex(random_bytes(16)) . '.webp';
        $finalPath = "{$directory}/{$finalName}";

        // Reprocessa imagem (remove qualquer payload malicioso)
        $image = match ($mime) {
            'image/jpeg', 'image/jpg', 'image/pjpeg' => imagecreatefromjpeg($file['tmp_name']),
            'image/png' => imagecreatefrompng($file['tmp_name']),
            'image/gif' => imagecreatefromgif($file['tmp_name']),
            'image/webp' => imagecreatefromwebp($file['tmp_name']),
            default => throw new RuntimeException('Formato não suportado'),
        };

        if (! $image) {
            throw new RuntimeException('Falha ao processar imagem.');
        }

        /**
         * Converte tudo para WEBP (performance + segurança)
         */
        if (! imagewebp($image, $finalPath, 85)) {
            unset($image); // opcional
            throw new RuntimeException('Falha ao salvar imagem em WebP.');
        }

        // opcional (nem precisa, mas se quiser ser explícito)
        unset($image);

        return self::$baseUrl . "/{$folder}/{$finalName}";
    }

    public static function update(
        array $file,
        string $folder,
        ?string $oldFileUrl
    ): string {
        self::checkInit();

         if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return $oldFileUrl ?? ''; 
        }
        
        $newUrl = self::save($file, $folder);

       

        if ($oldFileUrl && $oldFileUrl !== $newUrl) {
            self::delete($oldFileUrl);
        }

        return $newUrl;
    }

    public static function delete(string $fileUrl): bool
    {
        self::checkInit();

        $filePath = self::urlToPath($fileUrl);

        if (! file_exists($filePath)) {
            return false;
        }

        if (! unlink($filePath)) {
            throw new RuntimeException("Erro ao remover arquivo: {$filePath}");
        }

        $dir = dirname($filePath);
        if (is_dir($dir) && count(scandir($dir)) === 2) {
            rmdir($dir);
        }

        return true;
    }

    private static function validateFile(array $file): array
    {
        // 1. Erro de upload
        if (! isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $msg = self::uploadErrorMessage($file['error'] ?? -1);
            throw new InvalidArgumentException("Erro no upload: {$msg}");
        }

        // 2. Tamanho
        if ($file['size'] > self::$maxFileSize) {
            $maxMb = round(self::$maxFileSize / 1024 / 1024);
            throw new InvalidArgumentException("Arquivo excede {$maxMb}MB.");
        }

        // 3. Garantia de upload HTTP
        if (! is_uploaded_file($file['tmp_name'])) {
            throw new InvalidArgumentException('Upload inválido.');
        }

        // 4. MIME real
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (! $mime || $mime === 'application/octet-stream') {
            throw new InvalidArgumentException('Não foi possível identificar o tipo do arquivo.');
        }

        // 5. Validação real de imagem
        $info = @getimagesize($file['tmp_name']);
        $mimeImage = $info['mime'] ?? null;

        if (! $mimeImage || $mime !== $mimeImage) {
            throw new InvalidArgumentException('Arquivo não é uma imagem válida.');
        }

        // 6. Lista branca
        if (! in_array($mime, self::$allowedMimes, true)) {
            throw new InvalidArgumentException("Tipo MIME não permitido: {$mime}");
        }

        return [
            'mime' => $mime,
            'info' => $info,
        ];
    }

    private static function urlToPath(string $fileUrl): string
    {
        $relativePath = str_replace(self::$baseUrl, '', $fileUrl);

        $relativePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
        $basePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, self::$basePath);

        return $basePath . $relativePath;
    }

    private static function sanitizePathSegment(string $segment): string
    {
        $clean = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $segment);

        if (! $clean) {
            throw new InvalidArgumentException("Nome inválido: {$segment}");
        }

        return $clean;
    }

    private static function checkInit(): void
    {
        if (! isset(self::$basePath, self::$baseUrl)) {
            throw new RuntimeException('FileService::init() não foi chamado.');
        }
    }

    private static function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => 'Excede limite do php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'Excede limite do formulário.',
            UPLOAD_ERR_PARTIAL => 'Upload incompleto.',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'Sem diretório temporário.',
            UPLOAD_ERR_CANT_WRITE => 'Erro ao gravar no disco.',
            UPLOAD_ERR_EXTENSION => 'Bloqueado por extensão PHP.',
            default => "Erro desconhecido ({$code})",
        };
    }
}
