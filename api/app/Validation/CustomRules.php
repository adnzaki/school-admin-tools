<?php

namespace App\Validation;

use CodeIgniter\Database\BaseBuilder;
use Config\Database;

class CustomRules
{
    private function prepareUniqueQuery(string $value, string $field, array $data): array
    {
        [$field, $ignoreField, $ignoreValue] = array_pad(explode(',', $field), 3, null);
        [$table, $column] = explode('.', $field);

        $builder = Database::connect()
            ->table($table)
            ->select('1')
            ->where($column, $value)
            ->where('deleted_at', null)
            ->limit(1);

        return [$builder, $ignoreField, $ignoreValue];
    }

    private function isUniqueWithCustomMessage(string $value, string $field, array $data, string $errorLangKey, ?string &$error = null): bool
    {
        [$builder, $ignoreField, $ignoreValue] = $this->prepareUniqueQuery($value, $field, $data);

        if (
            $ignoreField !== null && $ignoreField !== ''
            && $ignoreValue !== null && $ignoreValue !== ''
            && preg_match('/^\{(\w+)\}$/', $ignoreValue) !== 1
        ) {
            $builder->where("{$ignoreField} !=", $ignoreValue);
        }

        $exists = $builder->get()->getRow() !== null;

        if ($exists) {
            $error = lang("Validation.{$errorLangKey}");
        }

        return ! $exists;
    }

    public function is_unique_nip($value, string $field, array $data, ?string &$error = null): bool
    {
        return $this->isUniqueWithCustomMessage((string)$value, $field, $data, 'is_unique_nip', $error);
    }

    public function is_unique_nisn($value, string $field, array $data, ?string &$error = null): bool
    {
        return $this->isUniqueWithCustomMessage((string)$value, $field, $data, 'is_unique_nisn', $error);
    }

    public function is_unique_no_induk($value, string $field, array $data, ?string &$error = null): bool
    {
        return $this->isUniqueWithCustomMessage((string)$value, $field, $data, 'is_unique_no_induk', $error);
    }
}
