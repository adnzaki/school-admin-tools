<?php

namespace App\Validation;

use CodeIgniter\Database\BaseBuilder;
use Config\Database;

class CustomRules
{
    /**
     * Wajib diisi jika field lain memiliki nilai tertentu
     * Format: required_if[field_target,val1,val2,...]
     */
    public function required_if(mixed $value, string $params, array $data): bool
    {
        $paramArray  = explode(',', $params);
        $targetField = array_shift($paramArray); // Mengambil nama field target ('jenis_pegawai')
        $targetValue = $data[$targetField] ?? null;

        $isTargetMatched = in_array($targetValue, $paramArray, true);
        $isEmpty = ($value === null || (is_string($value) && trim($value) === ''));

        // Jika status pegawai cocok (PNS/PPPK) dan nilainya KOSONG -> GAGAL VALIDASI
        if ($isTargetMatched && $isEmpty) {
            return false;
        }

        // Jika status pegawai tidak cocok (Honorer) dan nilainya KOSONG -> LOLOS
        // Catatan: Jika Honorer mengisikan angka/string, validasi akan lanjut ke rule 'numeric'
        return true;
    }

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

        // Tambahkan filter institusi_id jika tersedia di $data
        if (isset($data['institusi_id'])) {
            $builder->where('institusi_id', $data['institusi_id']);
        }

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
