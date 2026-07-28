<?php

declare(strict_types=1);

namespace Src\Core;

abstract class Request
{
    use Validations;

    private array $errors = [];
    private array $validated = [];
    private array $skipped = [];
    private ?\PDO $pdo = null;

    public function __construct(protected array $params)
    {
        if (! empty($_FILES)) {
            $this->params = array_merge($this->params, $_FILES);
        }

        foreach ($this->params as $key => $value) {
            if (is_string($value)) {
                $this->params[$key] = trim($value);
            }
        }

        foreach ($this->rules() as $key => $fieldRules) {
            $value = $this->params[$key] ?? null;

            if (in_array('nullable', $fieldRules, true)) {
                $this->nullable($key, $value);

                if (isset($this->skipped[$key])) {
                    continue;
                }
            }

            foreach ($fieldRules as $rule) {
                if ($rule === 'nullable') {
                    continue;
                }
                [$method, $arg] = $this->parseRule($rule);

                if (! method_exists($this, $method)) {
                    throw new \Exception("Regra '{$method}' não existe");
                }

                $this->$method($key, $value, $arg);

                if (isset($this->errors[$key]) || isset($this->skipped[$key])) {
                    break;
                }

                if (array_key_exists($key, $this->validated)) {
                    $value = $this->validated[$key];
                }
            }
        }
    }

    abstract public function rules(): array;

    abstract public function messages(): array;

    public function validated(): array
    {
        return $this->validated;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return ! empty($this->errors);
    }

    public function redirectOnFail(?string $url = null): static
    {
        if (! $this->fails()) {
            return $this;
        }

        $_SESSION['validation_errors'] = $this->errors;
        $_SESSION['old_input'] = $this->params;

        $redirect = $url ?? $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $redirect);
        exit;
    }

    protected function pdo(): \PDO
    {
        if ($this->pdo === null) {
            $this->pdo = (new Database())->getConnection();
        }

        return $this->pdo;
    }

    protected function addError(string $key, $value, array $message): void
    {
        $ruleName = array_key_first($message);
        $customMessage = $this->messages()["{$key}.{$ruleName}"]
            ?? $this->messages()[$key]
            ?? $message[$ruleName];

        $this->errors[$key][] = [
            'value' => $value,
            'message' => $customMessage,
        ];

        unset($this->validated[$key]);
    }

    protected function addSuccess(string $key, $value): void
    {
        if (! isset($this->errors[$key])) {
            $this->validated[$key] = $value;
        }
    }

    protected function skipField(string $key): void
    {
        $this->skipped[$key] = true;
    }

    private function parseRule(string $rule): array
    {
        $parts = explode(':', $rule, 2);

        return [$parts[0], $parts[1] ?? null];
    }
}
