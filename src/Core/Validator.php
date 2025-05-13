<?php

namespace App\Core;

use App\Models\{ User };

class Validator
{
    private array $errors = [];
    private array $after = [];

    public function validate(array $data, array $rules): bool
    {
        foreach ($rules as $field => $ruleSet) {
            $value = $data[$field] ?? '';

            if (is_array($value)) {
                $value = '';
            } elseif (!is_scalar($value)) {
                $value = '';
            } else {
                $value = trim((string)$value);
                if (is_numeric($value)) {
                    $value = (int)$value;
                }
            }

            $rulesList = explode('|', $ruleSet);

            foreach ($rulesList as $rule) {
                $params = null;

                if (strpos($rule, ':') !== false) {
                    [$rule, $params] = explode(':', $rule, 2);
                }

                $method = 'validate' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    if ($params !== null) {
                        $this->$method($field, $value, $params);
                    } else {
                        $this->$method($field, $value);
                    }
                } else {
                    throw new \Exception("Regla de validación '$rule' no implementada.");
                }
            }
        }

        foreach ($this->after as $callback) {
            $callback($this);
        }

        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[] = ['field' => $field, 'message' => $message];
    }

    private function validateRequired(string $field, $value): void
    {
        if ($value === '' || $value === null) {
            $this->addError($field, 'Este campo es obligatorio.');
        }
    }

    private function validateEmail(string $field, $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'El email no es válido.');
        }
    }

    private function validateUnique(string $field, $value): void
    {
        $userModel = new User();
        if ($userModel->findByEmail($value)) {
            $this->addError($field, 'Este correo electrónico ya está registrado.');
        }
    }

    private function validateMin(string $field, $value, $min): void
    {
        if (strlen($value) < (int)$min) {
            $this->addError($field, "Debe tener al menos $min caracteres.");
        }
    }

    private function validateMax(string $field, $value, $max): void
    {
        if (strlen($value) > (int)$max) {
            $this->addError($field, "Debe tener como máximo $max caracteres.");
        }
    }

    private function validateString(string $field, $value): void
    {
        if (preg_match('/^\d+$/', $value)) {
            $this->addError($field, 'No puede contener solo números.');
        }
    }

    private function validateInteger(string $field, $value): void
    {
        if (!is_int($value)) {
            $this->addError($field, 'El campo debe ser un número entero.');
        }
    }

    private function validateSame(string $field, $value, $otherField): void
    {
        global $_POST;
        if (($value !== ($_POST[$otherField] ?? null))) {
            $this->addError($field, "El campo debe coincidir con $otherField.");
        }
    }

    private function validateRegex(string $field, $value, $pattern): void
    {
        if (!preg_match($pattern, $value)) {
            $this->addError($field, "Formato inválido.");
        }
    }
}
